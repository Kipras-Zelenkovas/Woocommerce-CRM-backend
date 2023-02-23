<?php

namespace App\Http\Controllers;

use Codexshaper\WooCommerce\Facades\Order;
use Codexshaper\WooCommerce\Facades\Report;
use Illuminate\Http\Request;

class Manager extends Controller
{

    private $shops = [
        [
            'url'       => 'https://smart-sticker.net/',
            'key'       => 'ck_4e62ec5a06b08657b098015fd2fcaa7abcea5ead',
            'secret'    => 'cs_c63e30a1a6f8b88915986a19b9f492d37469f70b'
        ],
        [
            'url'       => 'https://nordic.si/',
            'key'       => 'ck_5fdd5f2878417ea58468541a954106de054b7505',
            'secret'    => 'cs_f50c57e70e9bf4106b653c477975f76b72e03f3e'
        ]
    ];

    public function index(Request $request)
    {
        try {

            $request->validate([
                'shop'  => 'required|string'
            ]);

            $per_page = $request->per_page ? $request->per_page : 15;
            $page = $request->page ? $request->page : 1;

            config([
                'woocommerce.store_url' => $this->shops[((int) $request->shop) - 1]['url'],
                'woocommerce.consumer_key' => $this->shops[((int) $request->shop) - 1]['key'],
                'woocommerce.consumer_secret' => $this->shops[((int) $request->shop) - 1]['secret'],
            ]);

            $orders = Order::paginate($per_page, $page);

            return response()->json([
                'status'    => true,
                'data'      => $orders
            ]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function find(Request $request)
    {
        try {
            $request->validate([
                'id'    => 'required|string',
                'shop'  => 'required|string|max:2'
            ]);

            config([
                'woocommerce.store_url' => $this->shops[((int) $request->shop) - 1]['url'],
                'woocommerce.consumer_key' => $this->shops[((int) $request->shop) - 1]['key'],
                'woocommerce.consumer_secret' => $this->shops[((int) $request->shop) - 1]['secret'],
            ]);

            $id = $request->id;

            $order = Order::find($id);

            return response()->json($order);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function all(Request $request)
    {
        try {

            ini_set('max_execution_time', 1000);

            config([
                'woocommerce.store_url' => $this->shops[((int) $request->shop) - 1]['url'],
                'woocommerce.consumer_key' => $this->shops[((int) $request->shop) - 1]['key'],
                'woocommerce.consumer_secret' => $this->shops[((int) $request->shop) - 1]['secret'],
            ]);

            $res = [];

            $page = 1;
            $per_page = 100;

            $run = true;

            while ($run) {
                $orders = Order::paginate($per_page, $page);
                array_push($res, $orders);
                $page += 1;
                if (json_decode($orders)->meta->next_page == null) {
                    $run = false;
                }
            }

            return response()->json($res);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    //Orders reports
    public function orders(Request $request)
    {
        try {
            $request->validate([
                'shop' => 'required|string'
            ]);

            config([
                'woocommerce.store_url' => $this->shops[((int) $request->shop) - 1]['url'],
                'woocommerce.consumer_key' => $this->shops[((int) $request->shop) - 1]['key'],
                'woocommerce.consumer_secret' => $this->shops[((int) $request->shop) - 1]['secret'],
            ]);

            $reports = Report::orders();

            return response()->json($reports);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    //Top sellers reports
    public function top_sellers(Request $request)
    {
        try {
            $request->validate([
                'shop' => 'required|string'
            ]);

            config([
                'woocommerce.store_url' => $this->shops[((int) $request->shop) - 1]['url'],
                'woocommerce.consumer_key' => $this->shops[((int) $request->shop) - 1]['key'],
                'woocommerce.consumer_secret' => $this->shops[((int) $request->shop) - 1]['secret'],
            ]);

            $reports = Report::topSellers();

            return response()->json($reports);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function test(Request $request)
    {
        try {
            config([
                'woocommerce.store_url' => $this->shops[0]['url'],
                'woocommerce.consumer_key' => $this->shops[0]['key'],
                'woocommerce.consumer_secret' => $this->shops[0]['secret'],
            ]);

            $orders = Order::orderBy("id", "asc")->paginate(1, 10);

            return response()->json($orders);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }

    public function change_status(Request $request)
    {
        try {
            $request->validate([
                'id'     => 'required|string',
                'status' => 'required|string|max:12',
                'shop'   => 'required|string|max:2'
            ]);

            config([
                'woocommerce.store_url' => $this->shops[((int) $request->shop) - 1]['url'],
                'woocommerce.consumer_key' => $this->shops[((int) $request->shop) - 1]['key'],
                'woocommerce.consumer_secret' => $this->shops[((int) $request->shop) - 1]['secret'],
            ]);

            $id = $request->id;
            $status = [
                'status' => $request->status
            ];

            $order = Order::update($id, $status);

            return response()->json($order);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage());
        }
    }
}
