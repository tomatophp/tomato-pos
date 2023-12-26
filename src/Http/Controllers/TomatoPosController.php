<?php

namespace TomatoPHP\TomatoPos\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use TomatoPHP\TomatoCategory\Models\Category;
use TomatoPHP\TomatoInventory\Models\InventoryItem;
use TomatoPHP\TomatoPos\Models\PosSetting;
use ProtoneMedia\Splade\Facades\Splade;
use ProtoneMedia\Splade\Facades\Toast;
use TomatoPHP\TomatoAdmin\Facade\Tomato;
use TomatoPHP\TomatoEcommerce\Models\Cart;
use TomatoPHP\TomatoEcommerce\Services\Cart\ProductsServices;
use TomatoPHP\TomatoInventory\Facades\TomatoInventory;
use TomatoPHP\TomatoInventory\Models\Inventory;
use TomatoPHP\TomatoInventory\Tables\InventoryTable;
use TomatoPHP\TomatoOrders\Facades\TomatoOrdering;
use TomatoPHP\TomatoBranches\Models\Branch;
use TomatoPHP\TomatoOrders\Models\Order;
use TomatoPHP\TomatoOrders\Tables\OrderTable;
use TomatoPHP\TomatoProducts\Models\Product;

class TomatoPosController extends Controller
{

    private function branchID(){
        $setting = PosSetting::where('user_id', auth('web')->user()->id)->where('key', 'pos_branch_id')->first();
        if($setting){
            return $setting->value;
        }
        else {
            return setting('ordering_active_inventory_direct_branch');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $setting = PosSetting::where('user_id', auth('web')->user()->id)->where('key', 'pos_branch_id')->first();

        if(!$setting){
            return redirect()->route('admin.pos.settings');
        }
        $products = \TomatoPHP\TomatoProducts\Models\Product::query();

        if($request->has('search') && $request->get('search')){
            if(Str::of($request->get('search'))->contains('INVENTORY-')){
                $getProduct = InventoryItem::where('uuid', $request->get('search'))->first();
                if($getProduct){
                    $productItem = Product::find($getProduct->item_id);
                    $currentSession = $this->getSessionID();

                    $cart = Cart::query()
                        ->where('session_id', $currentSession)
                        ->where('product_id', $productItem->id)
                        ->whereJsonContains('options', $getProduct->options ?? [])
                        ->first();

                    if($cart){
                        $cart->qty+=1;
                        $cart->save();
                    }
                    else {
                        $price = ProductsServices::getProductPrice($productItem->id, $getProduct->options ?? []);

                        $cart = new Cart();
                        $cart->product_id = $productItem->id;
                        $cart->session_id = $currentSession;
                        $cart->item = $productItem->name;
                        $cart->price = $price->price;
                        $cart->vat = $price->vat;
                        $cart->discount = $price->discount;
                        $cart->qty = 1;
                        $cart->total = $price->collect();
                        if($productItem->has_options){
                            $cart->options = $getProduct->options ?? [];
                        }
                        else {
                            $cart->options = [];
                        }

                        $cart->save();

                        $request->merge([
                            "search" => null
                        ]);
                    }
                }

            }
            else {
                $products->where('name', 'LIKE','%'.$request->get('search').'%')
                    ->orWhere('barcode', $request->get('search'))
                    ->orWhere('sku', $request->get('search'));
            }
        }

        $products->where('is_activated', 1);

        if($request->has('category_id') && $request->get('category_id')){
            $products->where('category_id', $request->get('category_id'));
        }

        $products = $products->paginate(12);

        $currentSession = $this->getSessionID();

        $cart = \TomatoPHP\TomatoEcommerce\Models\Cart::where('session_id', $currentSession)->orderBy('id', 'desc')->get();

        $categories = Category::where('for', 'product-categories')->where('activated', 1)->get();
        return view('tomato-pos::index', [
            "products" => $products,
            "cart" => $cart,
            "currentSession" => $currentSession,
            "categories" => $categories
        ]);
    }

    public function create(){
        return view('tomato-pos::inventory.create');
    }

    public function clear(){
        $currentSession = $this->getSessionID();
        \TomatoPHP\TomatoEcommerce\Models\Cart::where('session_id', $currentSession)->delete();

        Toast::success(__('Cart Has Been Cleaned'))->autoDismiss(2);
        return back();
    }

    private function getSessionID(){
        $sessionID = 'uuid-' . auth('web')->user()->id. '-' . '0';

        if(!session()->has($sessionID)){
            $currentSession = \Illuminate\Support\Str::random(6);
            session()->put($sessionID, $currentSession);
        }
        else {
            $currentSession = session()->get($sessionID);
        }

        return $currentSession;
    }

    public function update(Request $request,Cart $cart)
    {
        $product = Product::where('id',$request->get('product_id'))->with('productMetas', function ($q){
            $q->where('key', 'options');
        })->first();

        $checkInventory = TomatoInventory::checkBranchInventory(
            productID: $product->id,
            branchID: $this->branchID(),
            qty: $cart? $request->get('qty') : 1,
            options: $request->get('options') ?? []
        );

        if(!$checkInventory){
            Toast::danger(__("Sorry This Item with selected options is out of stock"));
            return back();
        }

        if($request->get('qty') < 1){
            $cart->delete();
        }
        else {
            $request->merge([
                "total" => (($cart->price + $cart->vat) - $cart->discount) * $request->get('qty')
            ]);

            $cart->update($request->all());
        }

        return back();
    }

    public function options(Request $request){
        $request->validate([
            "product_id" => "required|int|exists:products,id",
            "options" => "sometimes|array|min:1"
        ]);

        $product = Product::where('id',$request->get('product_id'))->with('productMetas', function ($q){
            $q->where('key', 'options');
        })->first();

        $currentSession = $this->getSessionID();

        $cart = Cart::query()
            ->where('session_id', $currentSession)
            ->where('product_id', $request->get('product_id'))
            ->whereJsonContains('options', $request->get('options') ?? [])
            ->first();

        if($request->get('options')){
            $checkInventory = TomatoInventory::checkBranchInventory(
                productID: $product->id,
                branchID: $this->branchID(),
                qty: $cart?$request->get('qty') : 1,
                options: $request->get('options') ?? []
            );

            if(!$checkInventory){
                Toast::danger(__("Sorry This Item with selected options is out of stock"));
                return back();
            }
        }

        if($product->has_options && !$cart){
            return view('tomato-pos::pos.options', [
                "product" => $product
            ]);
        }
        else {
            $cart->qty+=1;
            $cart->save();

            return back();
        }
    }

    public function cart(Request $request){
        $request->validate([
            "product_id" => "required|int|exists:products,id",
            "options" => "sometimes|array|min:1"
        ]);

        $product = Product::where('id',$request->get('product_id'))->with('productMetas', function ($q){
            $q->where('key', 'options');
        })->first();

        $currentSession = $this->getSessionID();

        $cart = Cart::query()
            ->where('session_id', $currentSession)
            ->where('product_id', $request->get('product_id'))
            ->whereJsonContains('options', $request->get('options') ?? [])
            ->first();


        if(!$cart){
            $checkInventory = TomatoInventory::checkBranchInventory(
                productID: $product->id,
                branchID: $this->branchID(),
                qty: 1,
                options: $request->get('options') ?? []
            );


            if(!$checkInventory){
                Toast::danger(__("Sorry This Item with selected options is out of stock"));
                return back();
            }
        }
        else {
            $checkInventory = TomatoInventory::checkBranchInventory(
                productID: $product->id,
                branchID: $this->branchID(),
                qty: $cart->qty+1,
                options: $request->get('options') ?? []
            );


            if(!$checkInventory){
                Toast::danger(__("Sorry This Item with selected options is out of stock"));
                return back();
            }
        }


        if($cart){
            $cart->qty+=1;
            $cart->save();
        }
        else {
            $price = ProductsServices::getProductPrice($product->id, $request->get('options')??[]);

            $cart = new Cart();
            $cart->product_id = $request->get('product_id');
            $cart->session_id = $currentSession;
            $cart->item = $product->name;
            $cart->price = $price->price;
            $cart->vat = $price->vat;
            $cart->discount = $price->discount;
            $cart->qty = 1;
            $cart->total = $price->collect();
            if($product->has_options){
                $cart->options = $request->get('options');
            }
            else {
                $cart->options = [];
            }

            $cart->save();
        }

        return back();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function inventory(Request $request)
    {
        $query = Inventory::query();
        $query->where('is_transaction', 1);
        $query->where('to_branch_id', $this->branchID());
        if($request->has('date') && $request->get('date')){
            $query->whereDate('created_at', Carbon::parse($request->get('date'))->toDateString());
        }
        else {
            $query->whereDate('created_at', Carbon::now()->toDateString());
        }

        $table = new InventoryTable($query);

        return view('tomato-pos::inventory.index', [
            "table" => $table
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function orders(Request $request)
    {
        $query = Order::query();
        $query->where('source', 'POS');
        if($request->has('date') && $request->get('date')){
            $query->whereDate('created_at', Carbon::parse($request->get('date'))->toDateString());
        }
        else {
            $query->whereDate('created_at', Carbon::now()->toDateString());
        }

        $table = new OrderTable($query);
        return view('tomato-pos::orders.index', [
            "table" => $table
        ]);
    }

    public function order(Order $order)
    {
        return view('tomato-pos::orders.show', [
            'order' => $order
        ]);
    }

    public function settings(){
        $settings = [];
        $getSettingModel = PosSetting::where('user_id', auth('web')->user()->id)->get();
        foreach ($getSettingModel as $item){
            $settings[$item->key] = $item->value;
        }
        return view('tomato-pos::settings.index', [
            'settings' => $settings
        ]);
    }

    public function settingsUpdate(Request $request){
        foreach ($request->all() as $key=>$item){
            $checkExists = PosSetting::where('user_id', auth('web')->user()->id)->where('key', $key)->first();
            if($checkExists){
                $checkExists->value = $item;
                $checkExists->save();
            }
            else {
                $newSetting = new PosSetting();
                $newSetting->user_id = auth('web')->user()->id;
                $newSetting->key = $key;
                $newSetting->value = $item;
                $newSetting->save();
            }
        }

        Toast::success(__('Settings Has Been Update For Current User'))->autoDismiss(2);
        return back();
    }

    public function place(Request $request)
    {
        $currentSession = $this->getSessionID();
        $cart = \TomatoPHP\TomatoEcommerce\Models\Cart::where('session_id', $currentSession)->orderBy('id', 'desc')->get();
        $uuid = "POS-" . Str::random(6) . "-" . $cart->last()?->id;

        $request->merge([
            "uuid" => $uuid
        ]);

        $request->validate([
            "cash" => "required|numeric",
            "payment_method" => "required|string|in:cash,credit,wallet",
            "uuid" => "required|string|unique:orders,uuid"
        ]);



        if($request->get('account_id')){
            $account = $request->get('account_id');
        }
        else {
            $account = config('tomato-crm.model')::where('username', 'pos')->first();
            if(!$account){
                $account = config('tomato-crm.model')::create([
                    "name" => "POS",
                    "username" => "pos",
                    "email" => setting('site_email'),
                    "phone" => setting('site_phone'),
                    "address" => setting('site_address'),
                ]);
            }

            $account->toArray();
        }

        if($request->get('payment_method') === 'wallet'){
            if($account['username'] === 'POS'){
                Toast::danger(__('You can not use this account for payment with Wallet'))->autoDismiss(2);
                return back();
            }
            $getAccount = config('tomato-crm.model')::find($account['id']);
            if($getAccount->balance < $cart->sum('total')){
                Toast::danger(__('Sorry Account Balance is low please recharge first'))->autoDismiss(2);
                return back();
            }
        }


        $order = Order::create([
            "uuid" => $uuid,
            "items" => $cart->toArray(),
            "user_id" => auth('web')->user()->id,
            "branch_id" =>$this->branchID(),
            "account_id" => $account['id'],
            "name" => $account['name'],
            "phone" => $account['phone'],
            "address" => $account['address'] ?? null,
            "discount" => $cart->map(fn($item) => $item['discount']*$item['qty'])->sum(),
            "vat" => $cart->map(fn($item) => $item['vat']*$item['qty'])->sum(),
            "total" => $cart->sum('total'),
            "status" => setting('ordering_paid_status'),
            "is_approved" => true,
            "is_payed" => true,
            "source" => "POS",
            "payment_method" => $request->get('payment_method')
        ]);


        foreach ($cart as $item){
            $item->account_id = $account['id'];
            $item->session_id = null;
            $item->save();

            $order->ordersItems()->create($item->toArray());
        }

        if($request->get('payment_method') === 'wallet'){
            $getAccount->withdrew($order->total);
        }

        TomatoOrdering::setOrder($order)->log(__('Order Has Been Created From POS Success'));

        TomatoInventory::orderToInventory(order: $order, paid: true);

        TomatoOrdering::setOrder($order)->log(__('Order Has Been Moved From Inventory'));

        Toast::success(__('Order Has Been Placed Success'))->autoDismiss(2);
        return Splade::redirectAway(route('admin.pos.orders.show', $order->id));
    }

    public function account(){
        return view('tomato-pos::pos.account');
    }

    public function accountStore(Request $request){
        $request->validate([
            "name" => "required|string|max:255",
            "email" => "nullable|email|string|max:255",
            "phone" => "required|string|max:14|unique:accounts,username",
            "address" => "nullable|string",
        ]);

        $account = config('tomato-crm.model')::create([
            "name" => $request->get('name'),
            "email" => $request->get('email'),
            "phone" => $request->get('phone'),
            "address" => $request->get('address'),
            "username" => $request->get('phone'),
        ]);

        Toast::success(__('Account Added Success'))->autoDismiss(2);
        return redirect()->to('admin/pos?account_id=' . $account->id);
    }

    public function store(Request $request){
        $fromBranch = Branch::find($this->branchID());
        $toBranch = Branch::find($request->get('branch_id'));
        $request->merge([
            "to_branch_id" => $toBranch->id,
            "company_id" => $toBranch->company->id,
            "to_branch_id" => $fromBranch->id,
            "type" => "out",
            "is_transaction" => true,
            "status" => "pending",
            "user_id" => auth('web')->user()->id,
            "vat" => collect($request->get('items'))->map(function ($item){
                return $item['tax'] * $item['qty'];
            })->sum(),
            "discount" => collect($request->get('items'))->map(function ($item){
                return $item['discount'] * $item['qty'];
            })->sum(),
            "total" => collect($request->get('items'))->sum('total'),
        ]);

        $request->validate([
            "to_branch_id" => "required|int|exists:branches,id",
            'items' => ['required','array','min:1', function($attribute, $value, $fail) use ($request, $toBranch){
                if($request->get('type') === 'out'){
                    foreach ($request->get('items') as $item){
                        $ckeckQTY = TomatoInventory::checkBranchInventory($item['item']['id'], $toBranch->id, $item['qty'], $item['options']??[]);
                        if(!$ckeckQTY){
                            $fail(__('Sorry The Product') . ': ' . $item['item']['name'][app()->getLocale()] . ' '. __('Do Not have this QTY'));
                        }
                        else {
                            $checkIfExists = TomatoInventory::checkInventoryItemQty($item['item']['id'], $toBranch->id, $item['qty'], $item['options']??[]);
                            if(!$checkIfExists){
                                $fail(__('Sorry The Product') . ': ' . $item['item']['name'][app()->getLocale()] . ' '. __('Has Pending QTY on the Inventory Movement'));
                            }
                        }
                    }
                }
            }],
        ]);

        $response = Tomato::store(
            request: $request,
            model: \TomatoPHP\TomatoInventory\Models\Inventory::class,
            message: __('Inventory updated successfully'),
            redirect: 'admin.inventories.index',
        );

        foreach ($request->get('items') as $item){
            if(is_array($item['item'])){
                $name = $item['item']['name'][app()->getLocale()];
                $type = isset($item['item']['barcode']) ? 'product' : 'material';
                if($type === 'product'){
                    $item_type = Product::class;
                    $item_id = $item['item']['id'];
                }
                else {
                    $item_type = "\TomatoPHP\TomatoProduction\Models\Material::class";
                    $item_id = $item['item']['id'];
                }
            }
            else {
                $name = $item['item'];
                $type = 'item';
            }

            $response->record->inventoryItems()->create([
                'item_id' => $item_id??null,
                'item_type' => $item_type??null,
                'item' => $name,
                'qty' => $item['qty'],
                'price' => $item['price'],
                'discount' => $item['discount'],
                'tax' => $item['tax'],
                'total' => $item['total'],
                'options' => $item['options'] ?? [],
            ]);
        }

        TomatoInventory::log(
            inventroyID: $response->record->id,
            log: __('Inventory Movement Has been saved!'),
            status: $response->record->status
        );

        Toast::success(__("Transfer Request Has Been Created"))->autoDismiss(2);
        return back();
    }
}
