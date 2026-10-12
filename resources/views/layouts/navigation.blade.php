{{-- <nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav> --}}






{{-- <nav>

    <h1>POS System</h1>
<hr>
    <a href="{{ route('dashboard') }}">Dashboard</a> |
    <a href="{{ route('profile.edit') }}">Profile</a> |

    <a href="{{ route('companies.create') }}">Add Company</a> |
    <a href="{{ route('companies.index') }}">View Companies</a> |
    <a href="{{ route('companies.trash') }}">Company Trash</a> |

    <a href="{{ route('branches.create') }}">Add Branch</a> |
    <a href="{{ route('branches.index') }}">View Branches</a> |
    <a href="{{ route('branches.trash') }}">Branch Trash</a> |

    <a href="{{ route('warehouses.create') }}">Add Warehouse</a> |
    <a href="{{ route('warehouses.index') }}">View Warehouse</a> |
    <a href="{{ route('warehouses.trash') }}">Warehouse Trash</a> |

    <a href="{{ route('registers.create') }}">Add Register</a> |
    <a href="{{ route('registers.index') }}">View Registers</a> |
    <a href="{{ route('registers.trash') }}">Register Trash</a> |

    @can('open', App\Models\RegisterSession::class)
        <a href="{{ route('register-sessions.create') }}">Open Register</a> |
    @endcan

    @can('viewAny', App\Models\RegisterSession::class)
        <a href="{{ route('register-sessions.index') }}">Register Sessions</a> |
    @endcan

    <a href="{{ route('categories.create') }}">Add Category</a> |
    <a href="{{ route('categories.index') }}">View Categories</a> |
    <a href="{{ route('categories.trash') }}">Category Trash</a> |

    <a href="{{ route('brands.create') }}">Add Brand</a> |
    <a href="{{ route('brands.index') }}">View Brands</a> |
    <a href="{{route('brands.trash') }}">Brand Trash</a> |

    <a href="{{ route('units.create') }}">Add Unit</a> |
    <a href="{{ route('units.index') }}">View Units</a> |
    <a href="{{ route('units.trash') }}">Unit Trash</a> |

    <a href="{{ route('tax-classes.create') }}">Add Tax Class</a> |
    <a href="{{ route('tax-classes.index') }}">View Tax Classes</a> |
    <a href="{{ route('tax-classes.trash') }}">Tax Class Trash</a> |

    <a href="{{ route('tax-rates.create') }}">Add Tax-Rate</a> |
    <a href="{{ route('tax-rates.index') }}">View Tax-Rates</a> |
    <a href="{{ route('tax-rates.trash') }}">Tax-Rate Trash</a> |

    <a href="{{ route('products.create') }}">Add Product</a> |
    <a href="{{ route('products.index') }}">View Prodcuts</a> |
    <a href="{{ route('products.trash') }}">Product Trash</a> |

    <a href="{{ route('customer-groups.create') }}">Add Customer-Group</a> |
    <a href="{{ route('customer-groups.index') }}">View Customer-Groups</a> |
    <a href="{{ route('customer-groups.trash') }}">Customer-Group Trash</a> |

    <a href="{{ route('customers.create') }}">Add Customer</a> |
    <a href="{{ route('customers.index') }}">View Customers</a> |
    <a href="{{ route('customers.trash') }}">Customer Trash</a> |

    <a href="{{ route('suppliers.create') }}">Add Supplier</a> |
    <a href="{{ route('suppliers.index') }}">View Suppliers</a> |
    <a href="{{ route('suppliers.trash') }}">Supplier Trash</a> |

    <a href="{{ route('purchase-orders.create') }}">Add Purchase-Order</a> |
    <a href="{{ route('purchase-orders.index') }}">View Purchase-Orders</a> |

    <a href="{{ route('goods-receipts.index') }}">View Goods Receipts</a> |

    <a href="{{ route('inventory.index') }}">View Inventory</a> |

    <a href="{{ route('stock-transfers.create') }}">Create Stock Transfer</a> |
    <a href="{{ route('stock-transfers.index') }}">View Stock Transfers</a> |

    <a href="{{ route('stock-counts.create') }}">Create Stock Count</a> |
    <a href="{{ route('stock-counts.index') }}">View Stock Counts</a> |
    
    <a href="{{ route('sales.create') }}">Create Sale</a> |
    <a href="{{ route('sales.index') }}">View Sales</a> |

    <a href="{{ route('payments.index') }}">View Payments</a> |

    <a href="{{ route('cash-movements.create') }}">Create Cash Movement</a> |
    <a href="{{ route('cash-movements.index') }}">View Cash Movements</a> |

    <a href="{{ route('sale-returns.index') }}">View Sale Returns</a> |

    <a href="{{ route('refunds.index') }}">View Refunds</a> |

    <a href="{{ route('customer-receivables.index') }}">View Customer Receivables</a> |
    
    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit">Log Out</button>
    </form>
<hr>

</nav> --}}
















<aside class="app-sidebar">

    {{-- Logo / Brand --}}
    <div class="sidebar-brand">
        POS System
        <small>Management Platform</small>
    </div>


    {{-- Main --}}
    <div class="sidebar-section">
        Main
    </div>

    <nav class="sidebar-nav">

        <a
            href="{{ route('dashboard') }}"
            class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">⌂</span>
            <span>Dashboard</span>
        </a>

        {{-- <a
            href="{{ route('profile.edit') }}"
            class="sidebar-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">◉</span>
            <span>Profile</span>
        </a> --}}

    </nav>


    {{-- Organization --}}
    <div class="sidebar-section">
        Organization
    </div>

    <nav class="sidebar-nav">

        @can('users.view')
            <a
                href="{{ route('users.index') }}"
                class="sidebar-link {{ request()->routeIs('users.index') ? 'active' : '' }}"
            >
                <span class="sidebar-icon">♙</span>
                <span>Users</span>
            </a>
        @endcan

        @can('users.create')
            <a
                href="{{ route('users.create') }}"
                class="sidebar-link sidebar-sub-link {{ request()->routeIs('users.create') ? 'active' : '' }}"
            >
                <span>＋</span>
                <span>Add User</span>
            </a>
        @endcan

        @can('users.trash.view')
            <a
                href="{{ route('users.trash') }}"
                class="sidebar-link sidebar-sub-link sidebar-trash {{ request()->routeIs('users.trash') ? 'active' : '' }}"
            >
                <span>⌫</span>
                <span>User Trash</span>
            </a>
        @endcan

        <a
            href="{{ route('companies.index') }}"
            class="sidebar-link {{ request()->routeIs('companies.*') && !request()->routeIs('companies.trash') && !request()->routeIs('companies.create') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">▣</span>
            <span>Companies</span>
        </a>

        <a
            href="{{ route('companies.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('companies.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>Add Company</span>
        </a>

        <a
            href="{{ route('companies.trash') }}"
            class="sidebar-link sidebar-sub-link sidebar-trash {{ request()->routeIs('companies.trash') ? 'active' : '' }}"
        >
            <span>⌫</span>
            <span>Company Trash</span>
        </a>


        <a
            href="{{ route('branches.index') }}"
            class="sidebar-link {{ request()->routeIs('branches.index') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">⌖</span>
            <span>Branches</span>
        </a>

        <a
            href="{{ route('branches.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('branches.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>Add Branch</span>
        </a>

        <a
            href="{{ route('branches.trash') }}"
            class="sidebar-link sidebar-sub-link sidebar-trash {{ request()->routeIs('branches.trash') ? 'active' : '' }}"
        >
            <span>⌫</span>
            <span>Branch Trash</span>
        </a>


        <a
            href="{{ route('warehouses.index') }}"
            class="sidebar-link {{ request()->routeIs('warehouses.index') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">▤</span>
            <span>Warehouses</span>
        </a>

        <a
            href="{{ route('warehouses.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('warehouses.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>Add Warehouse</span>
        </a>

        <a
            href="{{ route('warehouses.trash') }}"
            class="sidebar-link sidebar-sub-link sidebar-trash {{ request()->routeIs('warehouses.trash') ? 'active' : '' }}"
        >
            <span>⌫</span>
            <span>Warehouse Trash</span>
        </a>


        <a
            href="{{ route('registers.index') }}"
            class="sidebar-link {{ request()->routeIs('registers.*') && !request()->routeIs('registers.create') && !request()->routeIs('registers.trash') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">▣</span>
            <span>Registers</span>
        </a>

        <a
            href="{{ route('registers.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('registers.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>Add Register</span>
        </a>

        <a
            href="{{ route('registers.trash') }}"
            class="sidebar-link sidebar-sub-link sidebar-trash {{ request()->routeIs('registers.trash') ? 'active' : '' }}"
        >
            <span>⌫</span>
            <span>Register Trash</span>
        </a>


        @can('open', App\Models\RegisterSession::class)

            <a
                href="{{ route('register-sessions.create') }}"
                class="sidebar-link {{ request()->routeIs('register-sessions.create') ? 'active' : '' }}"
            >
                <span class="sidebar-icon">▶</span>
                <span>Open Register</span>
            </a>

        @endcan


        @can('viewAny', App\Models\RegisterSession::class)

            <a
                href="{{ route('register-sessions.index') }}"
                class="sidebar-link {{ request()->routeIs('register-sessions.*') && !request()->routeIs('register-sessions.create') ? 'active' : '' }}"
            >
                <span class="sidebar-icon">◷</span>
                <span>Register Sessions</span>
            </a>

        @endcan

    </nav>


    {{-- Catalog --}}
    <div class="sidebar-section">
        Catalog
    </div>

    <nav class="sidebar-nav">

        <a
            href="{{ route('categories.index') }}"
            class="sidebar-link {{ request()->routeIs('categories.index') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">☷</span>
            <span>Categories</span>
        </a>

        <a
            href="{{ route('categories.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('categories.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>Add Category</span>
        </a>

        <a
            href="{{ route('categories.trash') }}"
            class="sidebar-link sidebar-sub-link sidebar-trash {{ request()->routeIs('categories.trash') ? 'active' : '' }}"
        >
            <span>⌫</span>
            <span>Category Trash</span>
        </a>


        <a
            href="{{ route('brands.index') }}"
            class="sidebar-link {{ request()->routeIs('brands.index') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">◇</span>
            <span>Brands</span>
        </a>

        <a
            href="{{ route('brands.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('brands.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>Add Brand</span>
        </a>

        <a
            href="{{ route('brands.trash') }}"
            class="sidebar-link sidebar-sub-link sidebar-trash {{ request()->routeIs('brands.trash') ? 'active' : '' }}"
        >
            <span>⌫</span>
            <span>Brand Trash</span>
        </a>


        <a
            href="{{ route('units.index') }}"
            class="sidebar-link {{ request()->routeIs('units.index') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">≡</span>
            <span>Units</span>
        </a>

        <a
            href="{{ route('units.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('units.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>Add Unit</span>
        </a>

        <a
            href="{{ route('units.trash') }}"
            class="sidebar-link sidebar-sub-link sidebar-trash {{ request()->routeIs('units.trash') ? 'active' : '' }}"
        >
            <span>⌫</span>
            <span>Unit Trash</span>
        </a>


        <a
            href="{{ route('tax-classes.index') }}"
            class="sidebar-link {{ request()->routeIs('tax-classes.*') && !request()->routeIs('tax-classes.create') && !request()->routeIs('tax-classes.trash') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">%</span>
            <span>Tax Classes</span>
        </a>

        <a
            href="{{ route('tax-classes.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('tax-classes.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>Add Tax Class</span>
        </a>

        <a
            href="{{ route('tax-classes.trash') }}"
            class="sidebar-link sidebar-sub-link sidebar-trash {{ request()->routeIs('tax-classes.trash') ? 'active' : '' }}"
        >
            <span>⌫</span>
            <span>Tax Class Trash</span>
        </a>


        <a
            href="{{ route('tax-rates.index') }}"
            class="sidebar-link {{ request()->routeIs('tax-rates.*') && !request()->routeIs('tax-rates.create') && !request()->routeIs('tax-rates.trash') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">%</span>
            <span>Tax Rates</span>
        </a>

        <a
            href="{{ route('tax-rates.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('tax-rates.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>Add Tax Rate</span>
        </a>

        <a
            href="{{ route('tax-rates.trash') }}"
            class="sidebar-link sidebar-sub-link sidebar-trash {{ request()->routeIs('tax-rates.trash') ? 'active' : '' }}"
        >
            <span>⌫</span>
            <span>Tax Rate Trash</span>
        </a>


        <a
            href="{{ route('products.index') }}"
            class="sidebar-link {{ request()->routeIs('products.*') || request()->routeIs('product-*.*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">▦</span>
            <span>Products</span>
        </a>

        <a
            href="{{ route('products.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('products.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>Add Product</span>
        </a>

        <a
            href="{{ route('products.trash') }}"
            class="sidebar-link sidebar-sub-link sidebar-trash {{ request()->routeIs('products.trash') ? 'active' : '' }}"
        >
            <span>⌫</span>
            <span>Product Trash</span>
        </a>

    </nav>


    {{-- Customers --}}
    <div class="sidebar-section">
        Customers
    </div>

    <nav class="sidebar-nav">

        <a
            href="{{ route('customer-groups.index') }}"
            class="sidebar-link {{ request()->routeIs('customer-groups.*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">◎</span>
            <span>Customer Groups</span>
        </a>

        <a
            href="{{ route('customers.index') }}"
            class="sidebar-link {{ request()->routeIs('customers.*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">♙</span>
            <span>Customers</span>
        </a>

        <a
            href="{{ route('customers.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('customers.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>Add Customer</span>
        </a>

        <a
            href="{{ route('customers.trash') }}"
            class="sidebar-link sidebar-sub-link sidebar-trash {{ request()->routeIs('customers.trash') ? 'active' : '' }}"
        >
            <span>⌫</span>
            <span>Customer Trash</span>
        </a>

        <a
            href="{{ route('customer-receivables.index') }}"
            class="sidebar-link {{ request()->routeIs('customer-receivables.*') || request()->routeIs('customers.receivables') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">$</span>
            <span>Receivables</span>
        </a>

        <a
            href="{{ route('customer-payments.index') }}"
            class="sidebar-link {{ request()->routeIs('customer-payments.*') || request()->routeIs('customers.payments.*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">₨</span>
            <span>Customer Payments</span>
        </a>

    </nav>


    {{-- Suppliers / Purchasing --}}
    <div class="sidebar-section">
        Purchasing
    </div>

    <nav class="sidebar-nav">

        <a
            href="{{ route('suppliers.index') }}"
            class="sidebar-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">♜</span>
            <span>Suppliers</span>
        </a>

        <a
            href="{{ route('suppliers.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('suppliers.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>Add Supplier</span>
        </a>

        <a
            href="{{ route('suppliers.trash') }}"
            class="sidebar-link sidebar-sub-link sidebar-trash {{ request()->routeIs('suppliers.trash') ? 'active' : '' }}"
        >
            <span>⌫</span>
            <span>Supplier Trash</span>
        </a>


        <a
            href="{{ route('purchase-orders.index') }}"
            class="sidebar-link {{ request()->routeIs('purchase-orders.*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">▤</span>
            <span>Purchase Orders</span>
        </a>

        <a
            href="{{ route('purchase-orders.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('purchase-orders.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>New Purchase Order</span>
        </a>


        <a
            href="{{ route('goods-receipts.index') }}"
            class="sidebar-link {{ request()->routeIs('goods-receipts.*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">⇩</span>
            <span>Goods Receipts</span>
        </a>

    </nav>


    {{-- Inventory --}}
    <div class="sidebar-section">
        Inventory
    </div>

    <nav class="sidebar-nav">

        <a
            href="{{ route('inventory.index') }}"
            class="sidebar-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">▥</span>
            <span>Inventory</span>
        </a>

        <a
            href="{{ route('stock-transfers.index') }}"
            class="sidebar-link {{ request()->routeIs('stock-transfers.*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">⇄</span>
            <span>Stock Transfers</span>
        </a>

        <a
            href="{{ route('stock-transfers.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('stock-transfers.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>Create Transfer</span>
        </a>

        <a
            href="{{ route('stock-counts.index') }}"
            class="sidebar-link {{ request()->routeIs('stock-counts.*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">✓</span>
            <span>Stock Counts</span>
        </a>

        <a
            href="{{ route('stock-counts.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('stock-counts.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>Create Stock Count</span>
        </a>

    </nav>


    {{-- Sales --}}
    <div class="sidebar-section">
        Sales
    </div>

    
    @can('create', \App\Models\Sale::class)
        <a
            href="{{ route('pos.index') }}"
            class="sidebar-link {{ request()->routeIs('pos.*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">▣</span>
            <span>Cashier Terminal (POS)</span>
        </a>
    @endcan

    <nav class="sidebar-nav">

        <a
            href="{{ route('sales.index') }}"
            class="sidebar-link {{ request()->routeIs('sales.*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">▣</span>
            <span>Sales</span>
        </a>

        <a
            href="{{ route('sales.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('sales.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>Create Sale</span>
        </a>

        <a
            href="{{ route('payments.index') }}"
            class="sidebar-link {{ request()->routeIs('payments.*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">₨</span>
            <span>Payments</span>
        </a>

        <a
            href="{{ route('sale-returns.index') }}"
            class="sidebar-link {{ request()->routeIs('sale-returns.*') || request()->routeIs('sales.returns.*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">↩</span>
            <span>Sale Returns</span>
        </a>

        <a
            href="{{ route('refunds.index') }}"
            class="sidebar-link {{ request()->routeIs('refunds.*') || request()->routeIs('sale-returns.refunds.*') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">↪</span>
            <span>Refunds</span>
        </a>

    </nav>


    {{-- Cash Management --}}
    <div class="sidebar-section">
        Cash Management
    </div>

    <nav class="sidebar-nav">

        <a
            href="{{ route('cash-movements.index') }}"
            class="sidebar-link {{ request()->routeIs('cash-movements.index') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">▤</span>
            <span>Cash Movements</span>
        </a>

        <a
            href="{{ route('cash-movements.create') }}"
            class="sidebar-link sidebar-sub-link {{ request()->routeIs('cash-movements.create') ? 'active' : '' }}"
        >
            <span>＋</span>
            <span>Cash In / Out</span>
        </a>

    </nav>

    {{-- Reports --}}
    <div class="sidebar-section">
        Reports
    </div>

    <nav class="sidebar-nav">

        @can('viewAny', App\Models\Sale::class)

            <a
                href="{{ route('reports.sales') }}"
                class="sidebar-link {{ request()->routeIs('reports.sales') ? 'active' : '' }}"
            >
                <span class="sidebar-icon">▤</span>
                <span>Sales Report</span>
            </a>

        @endcan

        @can('viewAny', App\Models\StockBalance::class)

            <a
                href="{{ route('reports.inventory') }}"
                class="sidebar-link {{ request()->routeIs('reports.inventory') ? 'active' : '' }}"
            >
                <span class="sidebar-icon">▥</span>
                <span>Inventory Report</span>
            </a>

        @endcan

        <a
            href="{{ route('reports.purchasing') }}"
            class="sidebar-link {{ request()->routeIs('reports.purchasing') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">▤</span>
            <span>Purchasing Report</span>
        </a>

        <a
            href="{{ route('reports.customers') }}"
            class="sidebar-link {{ request()->routeIs('reports.customers') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">♙</span>
            <span>Customer Report</span>
        </a>

        <a
            href="{{ route('reports.suppliers') }}"
            class="sidebar-link {{ request()->routeIs('reports.suppliers') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">▤</span>
            <span>Supplier Report</span>
        </a>

        <a
            href="{{ route('reports.profitability') }}"
            class="sidebar-link {{ request()->routeIs('reports.profitability') ? 'active' : '' }}"
        >
            <span class="sidebar-icon">▥</span>
            <span>Profitability Report</span>
        </a>

    </nav>

    {{-- Account --}}
    <div class="sidebar-section">
        Account
    </div>

    <nav class="sidebar-nav">

        <form
            method="POST"
            action="{{ route('logout') }}"
            class="sidebar-logout-form"
        >

            @csrf

            <button
                type="submit"
                class="sidebar-link sidebar-logout"
            >
                <span class="sidebar-icon">⇥</span>
                <span>Log Out</span>
            </button>

        </form>

    </nav>

</aside>