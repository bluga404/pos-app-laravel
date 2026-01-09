<?php

namespace App\View\Components\Admin;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Aside extends Component
{
    /**
     * Create a new component instance.
     */
    public $routes;
    public function __construct()
    {
        $this->routes = [
            [
                'label' => 'Dashboard',
                'icon' => 'fas fa-home',
                'route_name' => 'dashboard',
                'route_active' => 'dashboard',
                'is_dropdown' => false,
            ],
            [
                'label' => 'User',
                'icon' => 'fas fa-users',
                'route_name' => 'users.index',
                'route_active' => 'users.*',
                'is_dropdown' => false,
            ],
            [
                'label' => 'Master Data',
                'icon' => 'fas fa-database',
                'route_active' => 'master-data.*',
                'is_dropdown' => true,
                'dropdown' => [
                    [
                        'label' => 'Kategori',
                        'route_name' => 'master-data.kategori.index',
                        'route_active' => 'master-data.kategori.*'
                    ],
                    [
                        'label' => 'Produk',
                        'route_name' => 'master-data.produk.index',
                        'route_active' => 'master-data.produk.*'
                    ]
                ]
            ],
            [
                'label' => 'Penerimaan Barang',
                'icon' => 'fas fa-truck-loading',
                'route_name' => 'penerimaan-barang.index',
                'route_active' => 'penerimaan-barang.*',
                'is_dropdown' => false,
            ],
            [
                'label' => 'Pengeluaran Barang',
                'icon' => 'fas fa-store',
                'route_name' => 'pengeluaran-barang.index',
                'route_active' => 'pengeluaran-barang.*',
                'is_dropdown' => false,
            ],
            [
                'label' => 'Laporan',
                'icon' => 'fas fa-file-invoice',
                'route_active' => 'laporan.*',
                'is_dropdown' => true,
                'dropdown' => [
                    [
                        'label' => 'Penerimaan Barang',
                        'route_name' => 'laporan.penerimaan-barang.laporan',
                        'route_active' => 'laporan.penerimaan-barang.*'
                    ],
                    [
                        'label' => 'Transaksi Barang',
                        'route_name' => 'laporan.pengeluaran-barang.laporan',
                        'route_active' => 'laporan.pengeluaran-barang.*'
                    ],
                ]
            ],
        ];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admin.aside');
    }
}
