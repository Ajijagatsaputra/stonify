<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\MetodePembayaran;
use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;

class PesananController extends Controller
{
    public function index()
    {

        // dd(Auth::user()->role->name);
        if (request()->ajax()) {

            if(Auth::user()->role->name  != 'user'){
                $order = Order::whereNotIn('status', ['pending', 'failed', 'cancelled'])->orderBy('created_at', 'desc')->get();

                return DataTables::of($order)
                ->addIndexColumn()
                ->addColumn('nama_customer', function ($order) {
                    return $order->user->name;
                })
                ->addColumn('order_id', function ($order) {
                    return $order->midtrans->order_id;
                })
                ->editColumn('total', function ($order) {
                    return 'Rp. ' . number_format($order->total, 0, ',', '.');
                })
                ->addColumn('payment_method', function ($order) {
                    return MetodePembayaran::where('kode', $order->payment_method)->first()->nama;
                })
                ->editColumn('status', function ($order) {
                    if ($order->status == 'pending') {
                        return '<span class="badge bg-warning">Menunggu Pembayaran</span>';
                    } elseif ($order->status == 'paid') {
                        return '<span class="badge bg-success">Pembayaran Berhasil</span>';
                    } elseif ($order->status == 'failed') {
                        return '<span class="badge bg-danger">Pembayaran Gagal</span>';
                    } elseif ($order->status == 'cancelled') {
                        return '<span class="badge bg-danger">Pesanan Dibatalkan</span>';
                    } elseif ($order->status == 'shipping') {
                        return '<span class="badge bg-info">Dalam Pengiriman</span>';
                    } elseif ($order->status == 'success') {
                        return '<span class="badge bg-success">Pesanan Selesai</span>';
                    } else {
                        return '<span class="badge bg-secondary">Status Tidak Diketahui</span>';
                    }
                })
                ->editColumn('created_at', function ($order) {
                    return $order->created_at->format('d-m-Y H:i:s');
                })
                ->addColumn('action', function ($order) {
                    if ($order->status == 'pending') {
                        return '<div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$order->id.'" data-original-title="Show" class="detail btn btn-primary btn-xs"><i class="fas fa-eye"></i></a>
                                    &nbsp;
                                    <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$order->midtrans->snap_token.'" data-original-title="Bayar" class="bayar btn btn-success btn-xs"><i class="fas fa-money-bill-wave"></i></a>
                                    &nbsp;
                                    <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$order->id.'" data-original-title="Delete" class="delete btn btn-danger btn-xs"><i class="fas fa-times"></i></a>
                                </div>';
                    } elseif ($order->status == 'paid' || $order->status == 'processing' || $order->status == 'shipping' || $order->status == 'success') {
                        return '<div style="display: flex; gap: 5px;">
                                                                <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$order->id.'" data-original-title="Show" class="detail btn btn-primary btn-xs"><i class="fas fa-eye"></i></a>

                        </div>';
                    } else {
                        return '-';
                    }

                })
                ->rawColumns(['action','status'])
                ->make(true);
            } else {
                $order = Order::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();

                return DataTables::of($order)
                ->addIndexColumn()
                ->addColumn('nama_customer', function ($order) {
                    return $order->user->name;
                })
                ->addColumn('order_id', function ($order) {
                    return $order->midtrans->order_id;
                })
                ->editColumn('total', function ($order) {
                    return 'Rp. ' . number_format($order->total, 0, ',', '.');
                })
                ->addColumn('payment_method', function ($order) {
                    return MetodePembayaran::where('kode', $order->payment_method)->first()->nama;
                })
                ->editColumn('status', function ($order) {
                    if ($order->status == 'pending') {
                        return '<span class="badge bg-warning">Menunggu Pembayaran</span>';
                    } elseif ($order->status == 'paid') {
                        return '<span class="badge bg-success">Pembayaran Berhasil</span>';
                    } elseif ($order->status == 'failed') {
                        return '<span class="badge bg-danger">Pembayaran Gagal</span>';
                    } elseif ($order->status == 'cancelled') {
                        return '<span class="badge bg-danger">Pesanan Dibatalkan</span>';
                    } elseif ($order->status == 'shipping') {
                        return '<span class="badge bg-info">Dalam Pengiriman</span>';
                    } elseif ($order->status == 'success') {
                        return '<span class="badge bg-success">Pesanan Selesai</span>';
                    } else {
                        return '<span class="badge bg-secondary">Status Tidak Diketahui</span>';
                    }
                })
                ->editColumn('created_at', function ($order) {
                    return $order->created_at->format('d-m-Y H:i:s');
                })
                ->addColumn('action', function ($order) {
                    if ($order->status == 'pending') {
                        return '<div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$order->id.'" data-original-title="Show" class="detail btn btn-primary btn-xs"><i class="fas fa-eye"></i></a>
                                    &nbsp;
                                    <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$order->midtrans->snap_token.'" data-original-title="Bayar" class="bayar btn btn-success btn-xs"><i class="fas fa-money-bill-wave"></i></a>
                                    &nbsp;
                                    <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$order->id.'" data-original-title="Delete" class="delete btn btn-danger btn-xs"><i class="fas fa-times"></i></a>
                                </div>';
                    } elseif ($order->status == 'paid' || $order->status == 'processing' || $order->status == 'shipping' || $order->status == 'success') {
                        return '<div style="display: flex; gap: 5px;">
                                                                <a href="javascript:void(0)" data-toggle="tooltip" data-id="'.$order->id.'" data-original-title="Show" class="detail btn btn-primary btn-xs"><i class="fas fa-eye"></i></a>

                        </div>';
                    } else {
                        return '-';
                    }

                })
                ->rawColumns(['action','status'])
                ->make(true);
            }

        }
        return view('pesanan.index');
    }

    public function show($id)
    {
        $order = Order::with('items.product', 'user', 'alamat', 'midtrans')->findOrFail($id);
        if ($order->status == 'pending') {
            $order->status = 'Menunggu Pembayaran';
        } elseif ($order->status == 'paid') {
            $order->status = 'Pesanan Diproses';
        } elseif ($order->status == 'shipping') {
            $order->status = 'Dalam Pengiriman';
        } elseif ($order->status == 'success') {
            $order->status = 'Pesanan Selesai';
        } else {
            $order->status = 'Pembayaran Gagal';
        }

        $order->payment_method = MetodePembayaran::where('kode', $order->payment_method)->first()->nama;

        return response()->json($order);
    }

    public function proses(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        if ($order->status == 'pending') {
            $order->status = 'processing';
        } elseif ($order->status == 'paid') {
            $order->status = 'shipping';
        } elseif ($order->status == 'shipping') {
            $order->status = 'success';
        } else {
            $order->status = 'Pembayaran Gagal';
        }
        $order->save();
        return response()->json([
            'success' => true,
            'status' => $order->status,
            'message' => 'Status pesanan berhasil diupdate'
        ]);
    }

    public function destroy(Request $request, $id)
    {
        try {
            $order = Order::with(['midtrans'])->findOrFail($id);

            if ($order->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya pesanan dengan status pending yang dapat dibatalkan'
                ], 403);
            }

            if ($order->midtrans && $order->midtrans->snap_token) {
                \Midtrans\Config::$serverKey = config('midtrans.server_key');
                \Midtrans\Config::$isProduction = config('midtrans.is_production');

                try {
                    $cancel = \Midtrans\Transaction::cancel($order->midtrans->order_id);
                } catch (\Exception $e) {
                    Log::error('Midtrans cancellation failed: ' . $e->getMessage());
                }
            }

            $order->status = 'cancelled';
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibatalkan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membatalkan pesanan'
            ], 500);
        }
    }
}
