<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Cosmic Bar · Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <style>
    /* Seus estilos CSS permanecem os mesmos */
    :root{--bg:#0f0b17;--text:#e5e7eb;--muted:#94a3b8;--card:rgba(15,23,42,0.6);--border:rgba(255,255,255,0.1);--chip-ok-bg:rgba(16,185,129,0.18);--chip-ok-bd:rgba(16,185,129,0.35);--chip-warn-bg:rgba(251,191,36,0.18);--chip-warn-bd:rgba(251,191,36,0.35);--accent-a:#ffd166;--accent-b:#38bdf8;--shadow:0 10px 30px rgba(2,6,23,.35)}.theme-dark{--bg:#0f0b17;--text:#e5e7eb;--muted:#94a3b8;--card:rgba(15,23,42,0.6);--border:rgba(255,255,255,.1);--shadow:0 10px 30px rgba(2,6,23,.35)}.theme-light{--bg:#fff;--text:#0f172a;--muted:#475569;--card:rgba(255,255,255,0.75);--border:rgba(2,6,23,.1);--shadow:0 6px 20px rgba(2,6,23,.1)}.bg-stars{background:radial-gradient(2px 2px at 20% 30%,#fff8 50%,transparent 51%) 0 0/120px 120px,radial-gradient(1.5px 1.5px at 60% 70%,#fff6 50%,transparent 51%) 0 0/100px 100px,radial-gradient(1px 1px at 80% 20%,#fff5 50%,transparent 51%) 0 0/140px 140px,var(--bg);animation:twinkle 8s linear infinite}.bg-stars-light{background:radial-gradient(2px 2px at 20% 30%,#94a3b830 50%,transparent 51%) 0 0/140px 140px,radial-gradient(1.5px 1.5px at 65% 75%,#94a3b826 50%,transparent 51%) 0 0/120px 120px,radial-gradient(1px 1px at 80% 20%,#94a3b820 50%,transparent 51%) 0 0/160px 160px,var(--bg);animation:twinkle 10s linear infinite}@keyframes twinkle{to{background-position:140px 140px,120px 120px,160px 160px,0 0}}.cosmic-card{background:var(--card);border:1px solid var(--border);border-radius:1rem;box-shadow:var(--shadow)}.btn{border-radius:.75rem;padding:.5rem .75rem;border:1px solid var(--border);background:rgba(2,6,23,.5);color:var(--text)}.btn:hover{background:rgba(2,6,23,.65)}.btn-accent{background:linear-gradient(90deg,var(--accent-a),var(--accent-b));color:#0f172a;font-weight:600;border:0}.chip-ok{background:var(--chip-ok-bg);border:1px solid var(--chip-ok-bd);color:#d1fae5;border-radius:999px;padding:.25rem .5rem;font-size:.75rem}.chip-warn{background:var(--chip-warn-bg);border:1px solid var(--chip-warn-bd);color:#fde68a;border-radius:999px;padding:.25rem .5rem;font-size:.75rem}.input-base{border-radius:.75rem;border:1px solid var(--border);width:100%;padding:.5rem .75rem}.input-base:focus{outline:2px solid var(--accent-b);outline-offset:2px}.input-dark{background:rgba(2,6,23,.5);color:var(--text)}.input-light{background:rgba(255,255,255,0.7);color:var(--text)}
  </style>
</head>

<body
  x-data="{
    /* Tabs */
    tab: 'products',

    /* Produtos */
    isNewModalOpen: false,
    isEditModalOpen: false,
    isDeleteModalOpen: false,
    isStockModalOpen: false,
    isPriceModalOpen: false,
    isStockAddModalOpen: false,

    editingProduct: {},
    stockingProduct: {},
    priceProduct: {},
    deletingProductUrl: '',

    /* 💰 FINANCE */
    openExpenseModal: false,
    showDetailedList: false,

    /* 📦 STOCK */
    isCriticalStockModalOpen: false,

    /* Tema */
    theme: localStorage.getItem('cosmic-theme') || 'theme-dark',

    init(){
        document.documentElement.classList.add(this.theme);

        if (window.shouldOpenModal) {
            this.isNewModalOpen = true;
        }
    },

    toggleTheme(){
      document.documentElement.classList.remove(this.theme);
      this.theme = (this.theme === 'theme-dark') ? 'theme-light' : 'theme-dark';
      document.documentElement.classList.add(this.theme);
      localStorage.setItem('cosmic-theme', this.theme);
    }
  }"
  :class="theme === 'theme-dark' ? 'bg-stars' : 'bg-stars-light'"
  class="h-full text-[color:var(--text)]"
>

@if ($errors->any())
<script>
    window.shouldOpenModal = true;
</script>
@endif

  <header class="sticky top-0 z-40 backdrop-blur border-b border-[color:var(--border)]" :class="theme==='theme-dark' ? 'bg-[rgba(2,6,23,.6)]' : 'bg-white/70'">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="h-9 w-9 rounded-full grid place-items-center"
             style="background:linear-gradient(135deg,var(--accent-b),var(--accent-a));">
          <svg class="h-5 w-5 text-slate-900" viewBox="0 0 24 24" fill="currentColor">
            <path d="M7 2h10a1 1 0 0 1 1 1v1a6 6 0 0 1-5 5.917V18h4a1 1 0 1 1 0 2H7a1 1 0 1 1 0-2h4v-8.083A6 6 0 0 1 6 4V3a1 1 0 0 1 1-1Z"/>
          </svg>
        </div>
        <div>
          <div class="font-semibold">COSMIC BAR · Admin</div>
          <div class="text-xs text-[color:var(--muted)]">{{ __('ui.control_room') }}</div>
        </div>
      </div>

    <div class="flex items-center gap-3">
        <div class="flex gap-2">
        <a href="{{ route('locale.set','it') }}"
            class="px-2 py-1 text-xs rounded transition flex items-center gap-1
                    {{ app()->getLocale()==='it'
                        ? 'bg-white text-slate-900'
                        : 'bg-[rgba(2,6,23,.5)] text-[color:var(--text)] border border-[color:var(--border)] hover:bg-[rgba(2,6,23,.65)]' }}">
            <svg class="h-3.5 w-5" viewBox="0 0 3 2" aria-hidden="true"><path fill="#008C45" d="M0 0h1v2H0z"/><path fill="#F4F5F0" d="M1 0h1v2H1z"/><path fill="#CD212A" d="M2 0h1v2H2z"/></svg>
            IT
        </a>
         <a href="{{ route('locale.set','en') }}"
            class="px-2 py-1 text-xs rounded transition flex items-center gap-1
                    {{ app()->getLocale()==='en'
                        ? 'bg-white text-slate-900'
                        : 'bg-[rgba(2,6,23,.5)] text-[color:var(--text)] border border-[color:var(--border)] hover:bg-[rgba(2,6,23,.65)]' }}">
            <svg class="h-3.5 w-5" viewBox="0 0 60 30" aria-hidden="true"><path fill="#012169" d="M0 0h60v30H0z"/><path fill="#FFF" d="M0 0l60 30m0-30L0 30"/><path stroke="#C8102E" stroke-width="5" d="M0 0l60 30m0-30L0 30"/><path fill="#FFF" d="M30 0v30M0 15h60"/><path stroke="#C8102E" stroke-width="7" d="M30 0v30M0 15h60"/></svg>
            EN
        </a>
        <a href="{{ route('locale.set','es') }}"
            class="px-2 py-1 text-xs rounded transition flex items-center gap-1
                    {{ app()->getLocale()==='es'
                        ? 'bg-white text-slate-900'
                        : 'bg-[rgba(2,6,23,.5)] text-[color:var(--text)] border border-[color:var(--border)] hover:bg-[rgba(2,6,23,.65)]' }}">
<svg class="h-3.5 w-5" viewBox="0 0 750 500" aria-hidden="true">
    <rect width="750" height="500" fill="#c8102e"/>
    <rect width="750" height="250" y="125" fill="#fabd00"/>
    <circle cx="200" cy="250" r="40" fill="#c8102e" opacity="0.8"/>
</svg>            ES
        </a>
        </div>

        <button @click="toggleTheme"
                class="btn"
                :class="theme==='theme-dark' ? '' : 'bg-white/70'">
          <span x-show="theme==='theme-dark'" class="inline-flex items-center gap-1">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4a1 1 0 0 1 1 1v1a1 1 0 1 1-2 0V5a1 1 0 0 1 1-1Zm0 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm8-5a1 1 0 0 1 1 1 1 1 0 1 1-2 0 1 1 0 0 1 1-1ZM4 12a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/></svg>
            Light
          </span>
          <span x-show="theme==='theme-light'" class="inline-flex items-center gap-1">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 1 0 9.79 9.79Z"/></svg>
            Dark
          </span>
        </button>

        <div class="hidden md:flex items-center gap-2 text-sm">
          <span class="text-[color:var(--muted)]">{{ __('ui.welcome', ['name' => auth()->user()->name]) }}</span>
        </div>
        <form action="{{ route('admin.logout') }}" method="POST">
          @csrf
          <button class="btn">{{ __('ui.logout') }}</button>
        </form>
      </div>
    </div>
  </header>

  <div class="max-w-7xl mx-auto px-4 pt-6">
    <div class="flex flex-wrap gap-2">
        
        <button @click="tab='sales'"
            :class="tab==='sales' ? 'bg-white text-slate-900' : 'bg-[rgba(2,6,23,.5)] text-[color:var(--text)]'"
            class="px-4 py-2 rounded-xl border border-[color:var(--border)] hover:border-white/20 transition flex items-center gap-2">
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 3h18v2H3zm0 6h12v2H3zm0 6h18v2H3z"/>
            </svg>
            {{ __('ui.sales') }}
        </button>

        @if(auth()->user()->is_admin)
            <button @click="tab='products'"
                :class="tab==='products' ? 'bg-white text-slate-900' : 'bg-[rgba(2,6,23,.5)] text-[color:var(--text)]'"
                class="px-4 py-2 rounded-xl border border-[color:var(--border)] hover:border-white/20 transition flex items-center gap-2">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h10v2H4z"/></svg>
                {{ __('ui.products') }}
            </button>

            <button @click="tab='stock'"
                :class="tab==='stock' ? 'bg-white text-slate-900' : 'bg-[rgba(2,6,23,.5)] text-[color:var(--text)]'"
                class="px-4 py-2 rounded-xl border border-[color:var(--border)] hover:border-white/20 transition flex items-center gap-2">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 7l9-4 9 4v10l-9 4-9-4V7zm9 2L6 7.5v7L12 16l6-1.5v-7L12 9z"/></svg>
                {{ __('ui.inventory') }}
            </button>

            <button @click="tab='finance'"
                :class="tab==='finance' ? 'bg-white text-slate-900' : 'bg-[rgba(2,6,23,.5)] text-[color:var(--text)]'"
                class="px-4 py-2 rounded-xl border border-[color:var(--border)] hover:border-white/20 transition flex items-center gap-2">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h16v2H4zm0 6h10v2H4zm0 6h16v2H4z"/></svg>
                {{ __('ui.finance') }}
            </button>
        @endif
    </div>
</div>
  <main class="max-w-7xl mx-auto px-4 py-6 space-y-8">

@if(auth()->user()->is_admin)
<section x-show="tab==='products'" x-transition>

    {{-- ALERTAS --}}
    <div class="space-y-2 mb-4">
        {{-- Sucesso --}}
        @if(session('success'))
            <div class="p-4 rounded bg-green-200 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- Erros de validação (inclui unique) --}}
        @if($errors->any())
            <div class="p-4 rounded bg-red-100 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- KPIs -->
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="cosmic-card p-4">
          <div class="text-sm text-[color:var(--muted)]">{{ __('ui.active_prod') }}</div>
          <div class="mt-2 text-2xl font-semibold">{{ $activeProducts }}</div>
        </div>
        <div class="cosmic-card p-4">
          <div class="text-sm text-[color:var(--muted)]">{{ __('ui.low_stock') }}</div>
          <div class="mt-2 text-2xl font-semibold">{{ $lowStockAlerts }}</div>
        </div>
        <div class="cosmic-card p-4">
          <div class="text-sm text-[color:var(--muted)]">{{ __('ui.new_week') }}</div>
          <div class="mt-2 text-2xl font-semibold">{{ $newProductsThisWeek }}</div>
        </div>
        <div class="cosmic-card p-4">
          <div class="text-sm text-[color:var(--muted)]">{{ __('ui.avg_margin') }}</div>
          <div class="mt-2 text-2xl font-semibold">{{ $avgMargin }}</div>
        </div>
    </div>

    <!-- FILTERS -->
<form action="{{ route('admin.dashboard') }}" method="GET" class="mt-6 flex flex-wrap items-center gap-3">
    <input type="hidden" name="tab" value="products">

    <div class="relative w-full md:w-72">
        <input 
            type="text" 
            name="search" 
            value="{{ request('search') }}" 
            placeholder="{{ __('ui.search_sku') }}..."
            class="w-full px-3 py-2 rounded-xl border border-[color:var(--border)] pr-10"
            :class="theme==='theme-dark' ? 'bg-[rgba(2,6,23,.5)] text-white' : 'bg-white/70 text-gray-800'"
        >
        <button type="submit" class="absolute right-3 top-2.5 text-[color:var(--muted)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </button>
    </div>

    <select 
        name="category_id" 
        class="px-3 py-2 rounded-xl border border-[color:var(--border)] outline-none" 
        :class="theme==='theme-dark' ? 'bg-[rgba(2,6,23,.5)] text-white' : 'bg-white/70 text-gray-800'"
        onchange="this.form.submit()"
    >
        <option value="">{{ __('ui.all_categories') }}</option>
        @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>

    @if(request('search') || request('category_id'))
        <a href="{{ route('admin.dashboard', ['tab' => 'products']) }}" class="text-sm text-red-400 hover:underline">
            {{ __('ui.clear_filters') ?? 'Limpar Filtros' }}
        </a>
    @endif

    <div class="flex-1 text-right">
        <button @click="isNewModalOpen = true" type="button" class="px-4 py-2 rounded-xl btn-accent font-semibold">
         {{ __('ui.new_product') }}
        </button>
    </div>
</form>

    <!-- PRODUCT TABLE -->
    <div class="mt-4 cosmic-card overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full" style="background:transparent">
            <thead class="text-left text-sm border-b border-[color:var(--border)] text-[color:var(--muted)]">
                <tr>
                    <th class="px-4 py-3">{{__('ui.product')}}</th>
                    <th class="px-4 py-3">{{__('ui.sku')}}</th>
                    <th class="px-4 py-3">{{__('ui.category')}}</th>
                    <th class="px-4 py-3">{{__('ui.status')}}</th>
                    <th class="px-4 py-3 text-right">{{__('ui.actions')}}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                <tr class="border-t border-[color:var(--border)]" :class="theme==='theme-light' ? 'hover:bg-black/5' : 'hover:bg-white/5'">
                    <td class="px-4 py-3 font-medium whitespace-nowrap">{{ $product->name }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        {{ Str::upper(Str::substr($product->category->name, 0, 4)) .'-'. str_pad($product->id, 3, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ $product->category->name }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        @if ($product->status === 'active')<span class="chip-ok">Active</span>@else<span class="chip-warn">Inactive</span>@endif
                    </td>
                    <td class="px-4 py-3">
                    <div class="flex items-center gap-2 justify-end">
                        <button @click="editingProduct = {{ $product->toJson() }}; isEditModalOpen = true" class="btn text-sm">{{ __('ui.edit') }}</button>
                        <button @click="deletingProductUrl = '{{ route('admin.products.destroy', $product) }}'; isDeleteModalOpen = true" class="px-3 py-1.5 rounded-lg text-white text-sm" style="background:#dc262a">{{ __('ui.delete') }}</button>
                    </div>
                    </td>
                </tr>
                @empty
                <tr class="border-t border-[color:var(--border)]">
                    <td colspan="5" class="text-center py-6 text-[color:var(--muted)]">{{ __('ui.no_products_found') }}</td>
                </tr>
                @endforelse
            </tbody>
          </table>
        </div>
    </div>
</section>

@endif

@if(auth()->user()->is_admin)

<section x-show="tab==='stock'" x-transition x-cloak>
  <!-- KPIs -->
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <div class="cosmic-card p-4">
      <div class="text-sm text-[color:var(--muted)]">{{ __('ui.items_stock') }}</div>
      <div class="mt-2 text-2xl font-semibold">{{ number_format($itemsInStock, 0, ',', '.') }}</div>
    </div>
<div class="cosmic-card p-4 border-l-4 
    @if($lowStockAlerts > 5) border-red-500 
    @elseif($lowStockAlerts > 0) border-yellow-500 
    @else border-transparent @endif">
    
    <div class="text-sm text-[color:var(--muted)]">{{ __('ui.reorder_need') }}</div>
    <div class="mt-2 text-2xl font-semibold">{{ $lowStockAlerts }}</div>
</div>
    <div class="cosmic-card p-4">
      <div class="text-sm text-[color:var(--muted)]">{{ __('ui.stock_value') }}</div>
      <div class="mt-2 text-2xl font-semibold">€{{ number_format($stockValue, 2, ',', '.') }}</div>
    </div>
    <div class="cosmic-card p-4">
      <div class="text-sm text-[color:var(--muted)]">{{ __('ui.days_cover') }}</div>
      <div class="mt-2 text-2xl font-semibold">N/A</div>
    </div>
  </div>

  @if (session('low_stock'))
    <div class="mt-4 p-3 rounded-lg border" style="background-color: var(--chip-warn-bg); border-color: var(--chip-warn-bd); color:#fde68a;">
      {{ session('low_stock') }}
    </div>
  @endif

  <!-- Botão Exportar Estoque Crítico -->
  <div class="mt-4 flex justify-end">
  <button
    @click="isCriticalStockModalOpen = true"
    class="btn btn-primary text-sm"
>
    {{ __('ui.export_critical_stock') }}
</button>

  </div>

  <!-- Tabela -->
  <div class="mt-6 cosmic-card overflow-hidden" x-data>
    <div class="overflow-x-auto">
      <table class="min-w-full" style="background:transparent">
        <thead class="text-left text-sm border-b border-[color:var(--border)] text-[color:var(--muted)]">
          <tr>
            <th class="px-4 py-3">{{ __('ui.product') }}</th>
            <th class="px-4 py-3">{{ __('ui.qty') }}</th>
            <th class="px-4 py-3">{{ __('ui.selling_price') }}</th>
            <th class="px-4 py-3">{{ __('ui.cost_price') }}</th>
            <th class="px-4 py-3">{{ __('ui.stock_alert_level') }}</th>
            <th class="px-4 py-3 text-right">{{ __('ui.actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($products as $product)
          <tr class="border-t border-[color:var(--border)]" :class="theme==='theme-light' ? 'hover:bg-black/5' : 'hover:bg-white/5'">
            <td class="px-4 py-3 font-medium whitespace-nowrap">{{ $product->name }}</td>
            <td class="px-4 py-3 whitespace-nowrap">{{ $product->stock->quantity ?? 0 }}</td>
            <td class="px-4 py-3 whitespace-nowrap">€{{ number_format($product->stock->price ?? 0, 2, ',') }}</td>
            <td class="px-4 py-3 whitespace-nowrap">€{{ number_format($product->stock->cost_price ?? 0, 2, ',') }}</td>
            <td class="px-4 py-3 whitespace-nowrap">{{ $product->stock->stock_alert_level ?? '-' }}</td>
            <td class="px-4 py-3 text-right">
              <div class="flex items-center gap-2 justify-end">
                <button
                  class="btn text-sm"
                  @click="
                    stockingProduct = @js($product->load(['stock.discounts']));
                    discounts = (stockingProduct.stock?.discounts || []).map(d => ({min_quantity: d.min_quantity, discounted_price: d.discounted_price}));
                    isStockModalOpen = true;
                  "
                >{{ __('ui.manage_stock') }}</button>

                <button
                  class="btn text-sm"
                  @click="
                    priceProduct = @js($product->load('stock'));
                    priceValue = priceProduct?.stock?.price ?? '';
                    isPriceModalOpen = true;
                  "
                >{{ __('ui.update_price') }}</button>
                <button
    class="btn text-sm"
    @click="
        stockProduct = @js($product->load('stock'));
        stockQuantity = 0;
        isStockAddModalOpen = true;
    "
>
    {{ __('ui.add_stock') }}
</button>

              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center py-6 text-[color:var(--muted)]">{{ __('ui.no_products_found') }}</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</section>

@endif

<section
  x-show="tab === 'sales'"
  x-transition
  x-cloak
  x-data="{
    mesaAtiva: 1,
    stockWarning: '',
    search: '',

    mesas: JSON.parse(localStorage.getItem('mesas_pdv')) || {
      1: [], 2: [], 3: [], 4: [], 5: [],
      6: [], 7: [], 8: [], 9: [], 10: []
    },

    save() {
      localStorage.setItem('mesas_pdv', JSON.stringify(this.mesas));
    },

    // Função de filtro corrigida (Busca por nome ou categoria)
    match(name, category) {
      if (!this.search) return true;
      const term = this.search.toLowerCase();
      return name.toLowerCase().includes(term) || category.toLowerCase().includes(term);
    },

    clearTable() {
      if (confirm('Deseja realmente limpar todos os itens desta mesa?')) {
        this.mesas[this.mesaAtiva] = [];
        this.stockWarning = '';
        this.save();
      }
    },

    addToCart(product) {
      const cart = this.mesas[this.mesaAtiva];
      const item = cart.find(i => i.id === product.id);

      if (item) {
        if (item.qty >= product.stock) {
          this.stockWarning = `⚠️ Só temos ${product.stock} unidades disponíveis`;
          return;
        }
        item.qty++;
      } else {
        if (product.stock <= 0) {
          this.stockWarning = '⚠️ Produto sem estoque disponível';
          return;
        }
        cart.push({ ...product, qty: 1 });
      }

      this.stockWarning = '';
      this.save();
    },

    removeItem(id) {
      this.mesas[this.mesaAtiva] = this.mesas[this.mesaAtiva].filter(i => i.id !== id);
      this.save();
    },

    total() {
      return this.mesas[this.mesaAtiva].reduce((t, i) => t + (i.price * i.qty), 0);
    },

    async checkout() {
      if (!this.mesas[this.mesaAtiva].length) {
        alert('A mesa está vazia!');
        return;
      }

      if (!confirm('Confirmar finalização da venda?')) return;

      try {
        const response = await fetch('/admin/sales/checkout', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
          },
          body: JSON.stringify({
            mesa: this.mesaAtiva,
            itens: this.mesas[this.mesaAtiva],
            total: this.total()
          })
        });

        const data = await response.json();
        if (!response.ok) throw new Error(data.error || 'Erro ao salvar venda');

        this.mesas[this.mesaAtiva] = [];
        this.save();
        alert('Venda finalizada com sucesso!');
        location.reload();
      } catch (error) {
        alert('Falha ao processar venda: ' + error.message);
      }
    }
  }"
  class="space-y-4"
>

  <div class="mb-4">
    <input
      type="text"
      placeholder="{{ __('ui.search_product') }}"
      x-model.debounce.300ms="search"
      class="w-full md:w-80 px-4 py-2 rounded-xl border border-[color:var(--border)] focus:ring-2 focus:ring-accent outline-none"
      :class="theme==='theme-dark' ? 'bg-[rgba(2,6,23,.5)] text-white' : 'bg-white/70 text-gray-800'"
    >
  </div>

  <div class="flex gap-2 flex-wrap">
    <template x-for="n in 10" :key="n">
      <button
        class="cosmic-card px-4 py-2 text-sm transition-all"
        :class="mesaAtiva === n ? 'border-2 border-accent bg-accent/10' : 'opacity-70'"
        @click="mesaAtiva = n"
      >
        {{ __('ui.table') }} <span x-text="n"></span>
      </button>
    </template>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    <div class="lg:col-span-3">
      <div class="grid sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($products as $product)
          @php
            $qty = $product->stock->quantity ?? 0;
            $price = $product->stock->price ?? 0;
            $catName = $product->category->name ?? 'Sem Categoria';
          @endphp

          @if($qty > 0)
            <div
              class="cosmic-card p-3 flex flex-col"
              x-show="match('{{ addslashes($product->name) }}', '{{ addslashes($catName) }}')"
            >
              <img
                src="{{ $product->photo_url ?? '/img/placeholder.png' }}"
                class="h-28 w-full object-cover rounded-lg mb-3"
                alt="{{ $product->name }}"
              >

              <div class="flex-1">
                <h3 class="font-semibold text-sm">{{ $product->name }}</h3>
                <p class="text-xs text-[color:var(--muted)]">
                  {{ $catName }}
                  <span class="block text-accent font-bold">
                    {{ __('ui.stock') }}: {{ $qty }}
                  </span>
                </p>
              </div>

              <div class="mt-3 flex items-center justify-between">
                <span class="font-semibold text-lg">
                  €{{ number_format($price, 2, ',', '.') }}
                </span>

                <button
                  class="btn btn-accent text-sm px-3 py-1"
                  @click="addToCart({
                    id: {{ $product->id }},
                    name: '{{ addslashes($product->name) }}',
                    price: {{ $price }},
                    stock: {{ $qty }},
                    photo_url: '{{ $product->photo_url ?? '/img/placeholder.png' }}'
                  })"
                >
                  {{ __('ui.add') }}
                </button>
              </div>
            </div>
          @endif
        @endforeach
      </div>
    </div>

    <div class="cosmic-card p-4 flex flex-col min-h-[500px] sticky top-4">
      <div class="flex justify-between items-center mb-4">
        <h2 class="font-semibold">
          {{ __('ui.order') }} – {{ __('ui.table') }} <span x-text="mesaAtiva"></span>
        </h2>
        <button @click="clearTable()" class="text-xs text-red-400 hover:underline">
            {{ __('ui.clear') ?? 'Limpar' }}
        </button>
      </div>

      <div class="flex-1 space-y-3 overflow-auto max-h-[400px]">
        <template x-if="!mesas[mesaAtiva].length">
          <p class="text-sm text-[color:var(--muted)] text-center py-10">
            {{ __('ui.empty_table') }}
          </p>
        </template>

        <template x-for="item in mesas[mesaAtiva]" :key="item.id">
          <div class="flex justify-between items-center text-sm border-b border-white/5 pb-2">
            <div class="flex items-center gap-2">
              <img :src="item.photo_url" class="h-10 w-10 object-cover rounded-lg">
              <div>
                <p x-text="item.name" class="font-medium truncate w-24"></p>
                <span class="text-xs text-[color:var(--muted)]">
                  €<span x-text="item.price.toFixed(2)"></span> ×
                  <span x-text="item.qty" class="text-white font-bold"></span>
                </span>
              </div>
            </div>

            <button
              class="bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white p-1 rounded transition-colors"
              @click="removeItem(item.id)"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </template>
      </div>

      <div class="border-t border-[color:var(--border)] mt-4 pt-4">
        <div class="flex justify-between font-semibold text-xl mb-4">
          <span>{{ __('ui.total') }}</span>
          <span class="text-accent">€<span x-text="total().toFixed(2)"></span></span>
        </div>

        <button
          class="btn btn-accent w-full py-3 font-bold shadow-lg shadow-accent/20 disabled:opacity-50"
          :disabled="!mesas[mesaAtiva].length"
          @click="checkout()"
        >
          {{ __('ui.checkout') }}
        </button>
      </div>
    </div>

  </div>
</section>

@if(auth()->user()->is_admin)
<section 
    x-show="tab === 'finance'" 
    x-transition 
    x-cloak 
    class="space-y-6"
>
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">
            {{ __('ui.finance_details') }}
        </h2>

       <div class="flex gap-2">
        <!-- note este botão -->
        <button 
            @click="openExpenseModal = true"
            class="text-xs bg-green-600/20 hover:bg-green-600/40 text-green-400 px-3 py-1 rounded-lg border border-green-500/30 transition"
        >
            ➕ New Expense
        </button>

        <button 
            @click="showDetailedList = !showDetailedList"
            class="text-xs bg-accent/20 hover:bg-accent/40 text-accent px-3 py-1 rounded-lg border border-accent/30 transition"
        >
            <span x-text="showDetailedList ? '{{ __('ui.hide_details') }}' : '{{ __('ui.view_details') }}'"></span>
        </button>
    </div>
    </div>

    <!-- Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Hoje -->
       <!-- Hoje -->
<div class="cosmic-card p-6 border-l-4 border-green-500">
    <h3 class="text-sm font-semibold uppercase tracking-wider text-[color:var(--muted)]">
        {{ __('ui.today') }}
    </h3>

    <div class="mt-4 space-y-1 text-sm">
        <p class="text-2xl font-bold
            {{ $financeStats['diario']->resultado < 0 ? 'text-red-500' : 'text-green-400' }}">
            €{{ number_format($financeStats['diario']->resultado,2,',','.') }}
        </p>

        <p>💰 {{ __('ui.sales') }}: €{{ number_format($financeStats['diario']->vendas,2,',','.') }}</p>
        <p class="text-orange-400">📦 CMV: €{{ number_format($financeStats['diario']->cmv,2,',','.') }}</p>
        <p class="text-red-400">💡 {{ __('ui.expenses') }}: €{{ number_format($financeStats['diario']->despesas,2,',','.') }}</p>
    </div>
</div>


        <!-- Semana -->
       <!-- Semana -->
<div class="cosmic-card p-6 border-l-4 border-purple-500">
    <h3 class="text-sm font-semibold uppercase tracking-wider text-[color:var(--muted)]">
        {{ __('ui.this_week') }}
    </h3>

    <div class="mt-4 space-y-1 text-sm">
        <p class="text-2xl font-bold
            {{ $financeStats['semanal']->resultado < 0 ? 'text-red-500' : 'text-green-400' }}">
            €{{ number_format($financeStats['semanal']->resultado,2,',','.') }}
        </p>

        <p>💰 {{ __('ui.sales') }}: €{{ number_format($financeStats['semanal']->vendas,2,',','.') }}</p>
        <p class="text-orange-400">📦 CMV: €{{ number_format($financeStats['semanal']->cmv,2,',','.') }}</p>
        <p class="text-red-400">💡 {{ __('ui.expenses') }}: €{{ number_format($financeStats['semanal']->despesas,2,',','.') }}</p>
    </div>
</div>


      <!-- Mês -->
<div class="cosmic-card p-6 border-l-4 border-accent">
    <h3 class="text-sm font-semibold uppercase tracking-wider text-[color:var(--muted)]">
        {{ __('ui.this_month') }}
    </h3>

    <div class="mt-4 space-y-1 text-sm">

        <!-- RESULTADO FINAL -->
        <p class="text-2xl font-bold
            {{ $financeStats['mensal']->resultado < 0 ? 'text-red-500' : 'text-green-400' }}">
            €{{ number_format($financeStats['mensal']->resultado, 2, ',', '.') }}
        </p>

        <p class="text-xs text-[color:var(--muted)] mb-2">
            {{ __('ui.month_result') }}
        </p>

        <p>💰 {{ __('ui.sales') }}: €{{ number_format($financeStats['mensal']->vendas,2,',','.') }}</p>

        <p class="text-orange-400">
            📦 CMV: €{{ number_format($financeStats['mensal']->cmv,2,',','.') }}
        </p>

        <p class="text-red-400">
            💡 {{ __('ui.expenses') }}: €{{ number_format($financeStats['mensal']->despesas,2,',','.') }}
        </p>
    </div>
</div>


    </div>

    <!-- Detalhamento de Despesas -->
<!-- Detalhamento de Despesas -->
<div 
    x-show="showDetailedList" 
    x-transition 
    class="cosmic-card p-4 bg-white/5 border border-white/10"
>
    <h3 class="text-white font-semibold mb-4">
        💡 {{ __('ui.expenses_of_the_month') }}
    </h3>

    @if($expensesMonth->count())
        <table class="w-full text-sm">
            <thead class="text-[color:var(--muted)] border-b border-white/10">
                <tr>
                    <th class="text-left py-2">{{ __('ui.date') }}</th>
                    <th class="text-left">{{ __('ui.description') }}</th>
                    <th>{{ __('ui.category') }}</th>
                    <th>{{ __('ui.type') }}</th>
                    <th class="text-right">{{ __('ui.amount') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expensesMonth as $expense)
                    <tr class="border-b border-white/5">
                        <td class="py-1">{{ $expense->expense_date->format('d/m') }}</td>
                        <td>{{ $expense->description }}</td>
                        <td>{{ ucfirst($expense->category) }}</td>
                        <td class="capitalize text-xs">{{ $expense->type }}</td>
                        <td class="text-right text-red-400">
                            €{{ number_format($expense->amount,2,',','.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-sm text-[color:var(--muted)] italic">
            {{ __('ui.no_expenses_recorded') }}
        </p>
    @endif
</div>
<!-- Gráficos -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

    <!-- Resultado -->
<!-- Resultado Financeiro -->
<div class="cosmic-card p-4">
    <h3 class="text-sm font-semibold mb-3 text-white">
        📊 {{ __('ui.financial_result') }}
    </h3>

    <div class="relative h-56">
        <canvas id="resultChart"></canvas>
    </div>
</div>

<!-- Vendas x CMV x Despesas -->
<div class="cosmic-card p-4">
    <h3 class="text-sm font-semibold mb-3 text-white">
        💰 {{ __('ui.sales_vs_costs') }}
    </h3>

    <div class="relative h-56">
        <canvas id="financeChart"></canvas>
    </div>
</div>

</div>

<div class="cosmic-card p-4 mt-6">
    <h3 class="text-sm font-semibold mb-3 text-white">
        💡 {{ __('ui.expenses_by_category') }}
    </h3>

    <div class="relative h-64 max-w-md mx-auto">
        <canvas id="expenseCategoryChart"></canvas>
    </div>
</div>


<!-- Estoque -->
<div class="cosmic-card p-4 mt-4">
    <h2 class="font-semibold mb-4 text-lg">
        {{ __('ui.stock_investment_summary') }}
    </h2>

    <p class="text-sm text-[color:var(--muted)]">
        {{ __('ui.total_invested_stock') }}:
        <span class="text-white font-mono">
            €{{ number_format($stockValue ?? 0, 2, ',', '.') }}
        </span>
    </p>
</div>
</section>

@endif

  </main>
<div 
    x-show="openExpenseModal" 
    x-transition 
    class="fixed inset-0 bg-black/60 flex items-center justify-center z-50"
>
    <div class="bg-[#0f172a] rounded-xl p-6 w-full max-w-md">
        <h3 class="text-white font-semibold mb-4">
            ➕ {{ __('ui.new_expense') }}
        </h3>

        <form method="POST" action="{{ route('admin.expenses.store') }}" class="space-y-3">
            @csrf

            <input 
                type="date" 
                name="expense_date" 
                required 
                class="w-full rounded bg-white/10 text-white p-2"
            >

            <input 
                type="text" 
                name="description" 
                placeholder="{{ __('ui.expense_description_placeholder') }}"
                required 
                class="w-full rounded bg-white/10 text-white p-2"
            >

           <select 
    name="category" 
    class="w-full rounded bg-gray-800 text-white p-2 border border-gray-700"
>
    <option value="Acqua">{{ __('ui.expense_water') }}</option>
    <option value="Luce">{{ __('ui.expense_electricity') }}</option>
    <option value="Affitto">{{ __('ui.expense_rent') }}</option>
<option value="Gas">{{ __('ui.expense_gas') }}</option>
    <option value="internet">{{ __('ui.expense_internet') }}</option>
    <option value="Altri">{{ __('ui.expense_other') }}</option>
</select>

            <input 
                type="number" 
                step="0.01" 
                name="amount"
                placeholder="{{ __('ui.amount') }} €"
                required 
                class="w-full rounded bg-white/10 text-white p-2"
            >

            <div class="flex justify-end gap-2 pt-2">
                <button 
                    type="button" 
                    @click="openExpenseModal = false"
                    class="text-sm px-3 py-1 text-gray-300"
                >
                    {{ __('ui.cancel') }}
                </button>

                <button 
                    type="submit"
                    class="text-sm bg-green-600 px-4 py-1 rounded text-white"
                >
                    {{ __('ui.save') }}
                </button>
            </div>
        </form>
    </div>
</div>


  <div x-show="isNewModalOpen" style="display: none;" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0,0,0,0.5);">
    <div x-data="{ photoPreview: null, photoName: null, handlePhotoChange(event) { const file = event.target.files[0]; if (!file) { return; }; this.photoName = file.name; const reader = new FileReader(); reader.onload = (e) => { this.photoPreview = e.target.result; }; reader.readAsDataURL(file); } }" @click.outside="isNewModalOpen = false" x-show="isNewModalOpen" x-transition class="cosmic-card w-full max-w-lg backdrop-blur-lg">
        <div class="flex items-center justify-between p-4 border-b border-[color:var(--border)]">
            <h2 class="text-lg font-semibold">{{ __('ui.add_new_product') }}</h2>
            <button @click="isNewModalOpen = false" class="p-1 rounded-full hover:bg-white/10">&times;</button>
        </div>
        @if ($errors->any())<div class="p-4 mx-6 mt-4 rounded-lg bg-red-900/50 border border-red-500/50 text-red-300"><h4 class="font-semibold mb-2 text-red-200">{{ __('ui.validation_error_title') }}</h4><ul class="list-disc list-inside text-sm space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6 space-y-4">
                <div><label for="product_name" class="block text-sm font-medium text-[color:var(--muted)]">{{ __('ui.product_name') }}</label><input type="text" id="product_name" name="name" value="{{ old('name') }}" required class="input-base mt-1" :class="theme==='theme-dark' ? 'input-dark' : 'input-light'"></div>
                <div><label for="product_category" class="block text-sm font-medium text-[color:var(--muted)]">{{ __('ui.category') }}</label><select id="product_category" name="category_id" required class="input-base mt-1" :class="theme==='theme-dark' ? 'input-dark' : 'input-light'"><option value="">{{ __('ui.select_category') }}</option>@foreach($categories as $category)<option value="{{ $category->id }}" @if(old('category_id') == $category->id) selected @endif>{{ $category->name }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-medium text-[color:var(--muted)]">{{ __('ui.photo_optional') }}</label><div class="mt-1 flex items-center justify-center p-4 border-2 border-dashed rounded-md relative" style="border-color: var(--border);"><div x-show="!photoPreview" class="space-y-1 text-center"><svg class="mx-auto h-12 w-12 text-[color:var(--muted)]" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true"><path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path></svg><div class="flex text-sm text-[color:var(--muted)]"><label for="file-upload" class="relative cursor-pointer rounded-md font-medium" style="color:var(--accent-b);"><span>{{ __('ui.upload_file') }}</span><input x-ref="photoInput" @change="handlePhotoChange" id="file-upload" name="photo" type="file" class="sr-only" accept="image/*"></label><p class="pl-1">{{ __('ui.or_drag_drop') }}</p></div><p class="text-xs text-[color:var(--muted)]">PNG, JPG, GIF up to 10MB</p></div><div x-show="photoPreview" style="display: none;" class="text-center"><img :src="photoPreview" class="max-h-32 mx-auto rounded-lg"><p x-text="photoName" class="text-xs text-[color:var(--muted)] mt-2"></p><button @click="photoPreview = null; photoName = null; $refs.photoInput.value = null;" type="button" class="mt-2 text-xs btn">{{ __('ui.remove') }}</button></div></div></div>
            </div>
            <div class="flex justify-end gap-3 p-4 border-t border-[color:var(--border)]" :class="theme==='theme-dark' ? 'bg-[rgba(2,6,23,.5)]' : 'bg-slate-50/50'"><button type="button" @click="isNewModalOpen = false" class="btn">{{ __('ui.cancel') }}</button><button type="submit" class="btn btn-accent">{{ __('ui.save_product') }}</button></div>
        </form>
    </div>
  </div>

<!-- MODAL: EDIT PRODUCT -->
<div 
    x-show="isEditModalOpen" 
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <div 
        x-data="{
            photoPreview: null,
            photoName: null,
            handlePhotoChange(event) {
                const file = event.target.files[0];
                if (!file) return;
                this.photoName = file.name;
                const reader = new FileReader();
                reader.onload = (e) => { this.photoPreview = e.target.result; };
                reader.readAsDataURL(file);
            },
            // Resetar o preview quando o modal abrir/fechar
            init() {
                $watch('isEditModalOpen', value => {
                    if (value) {
                        this.photoPreview = editingProduct.photo_url || null;
                        this.photoName = null;
                    }
                })
            }
        }"
        @click.outside="isEditModalOpen = false" 
        class="cosmic-card w-full max-w-lg shadow-2xl"
    >
        <div class="flex items-center justify-between p-4 border-b border-[color:var(--border)]">
            <h2 class="text-lg font-semibold">{{ __('ui.edit_product') }}</h2>
            <button @click="isEditModalOpen = false" class="p-1 rounded-full hover:bg-white/10 text-xl">&times;</button>
        </div>

        <form :action="`/admin/products/${editingProduct.id}`" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="p-6 space-y-4">
                <div>
                    <label for="edit_product_name" class="block text-sm font-medium text-[color:var(--muted)]">{{ __('ui.product_name') }}</label>
                    <input type="text" id="edit_product_name" name="name" x-model="editingProduct.name" required class="input-base mt-1" :class="theme==='theme-dark' ? 'input-dark' : 'input-light'">
                </div>

                <div>
                    <label for="edit_product_category" class="block text-sm font-medium text-[color:var(--muted)]">{{ __('ui.category') }}</label>
                    <select id="edit_product_category" name="category_id" x-model="editingProduct.category_id" required class="input-base mt-1" :class="theme==='theme-dark' ? 'input-dark' : 'input-light'">
                        <option value="">{{ __('ui.select_category') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-[color:var(--muted)]">{{ __('ui.photo_optional') }}</label>
                    <div class="mt-1 flex items-center justify-center p-4 border-2 border-dashed rounded-md relative" style="border-color: var(--border);">
                        
                        <div x-show="!photoPreview" class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-[color:var(--muted)]" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <div class="flex text-sm text-[color:var(--muted)]">
                                <label for="edit_file_upload" class="relative cursor-pointer rounded-md font-medium" style="color:var(--accent-b);">
                                    <span>{{ __('ui.upload_file') }}</span>
                                    <input x-ref="photoInput" @change="handlePhotoChange" id="edit_file_upload" name="photo" type="file" class="sr-only" accept="image/*">
                                </label>
                            </div>
                        </div>

                        <div x-show="photoPreview" class="text-center">
                            <img :src="photoPreview" class="max-h-32 mx-auto rounded-lg shadow-md">
                            <p x-text="photoName" class="text-xs text-[color:var(--muted)] mt-2"></p>
                            <button @click="photoPreview = null; photoName = null; $refs.photoInput.value = null;" type="button" class="mt-2 text-xs text-red-400 hover:underline">{{ __('ui.remove') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 p-4 border-t border-[color:var(--border)]" :class="theme==='theme-dark' ? 'bg-black/20' : 'bg-slate-50'">
                <button type="button" @click="isEditModalOpen = false" class="btn">{{ __('ui.cancel') }}</button>
                <button type="submit" class="btn btn-accent">{{ __('ui.update_product') }}</button>
            </div>
        </form>
    </div>
</div>


 <div
    x-show="isCriticalStockModalOpen"
    x-transition
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm"
>
    <div class="cosmic-card w-full max-w-3xl p-5 relative">

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-white">
                🚨 {{ __('ui.critical_stock_list') }}
            </h3>
            <button
                @click="isCriticalStockModalOpen = false"
                class="text-[color:var(--muted)] hover:text-white"
            >✕</button>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto max-h-[60vh]">
            <table class="min-w-full text-sm">
                <thead class="border-b border-[color:var(--border)] text-[color:var(--muted)]">
                    <tr>
                        <th class="px-3 py-2 text-left">{{ __('ui.product') }}</th>
                        <th class="px-3 py-2 text-center">{{ __('ui.qty') }}</th>
                        <th class="px-3 py-2 text-center">{{ __('ui.stock_alert_level') }}</th>
                        <th class="px-3 py-2 text-center">{{ __('ui.cost_price') }}</th>
                        <th class="px-3 py-2 text-center">{{ __('ui.qty_to_reach_alert') }}</th>
                        <th class="px-3 py-2 text-center">{{ __('ui.total_cost') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $criticalProducts = $products->filter(fn($p) =>
                            $p->stock &&
                            $p->stock->quantity <= $p->stock->stock_alert_level
                        );
                        $grandTotal = 0;
                    @endphp

                    @forelse($criticalProducts as $product)
                        @php
                            $qtyToReachAlert = $product->stock->stock_alert_level - $product->stock->quantity;
                            $totalCost = $qtyToReachAlert * $product->stock->cost_price;
                            $grandTotal += $totalCost;
                        @endphp
                        <tr class="border-t border-[color:var(--border)]">
                            <td class="px-3 py-2 font-medium">{{ $product->name }}</td>
                            <td class="px-3 py-2 text-center text-red-400 font-semibold">{{ $product->stock->quantity }}</td>
                            <td class="px-3 py-2 text-center">{{ $product->stock->stock_alert_level }}</td>
                            <td class="px-3 py-2 text-center">€{{ number_format($product->stock->cost_price, 2, ',', '.') }}</td>
                            <td class="px-3 py-2 text-center text-green-400 font-semibold">{{ $qtyToReachAlert }}</td>
                            <td class="px-3 py-2 text-center font-medium">€{{ number_format($totalCost, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-[color:var(--muted)]">
                                {{ __('ui.no_critical_stock') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>

                @if($criticalProducts->count())
                    <tfoot>
                        <tr class="border-t border-[color:var(--border)] font-semibold text-white">
                            <td colspan="5" class="px-3 py-2 text-right">{{ __('ui.grand_total_cost') ?? 'Total Geral' }}</td>
                            <td class="px-3 py-2 text-center">€{{ number_format($grandTotal, 2, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        <!-- Footer -->
        <div class="mt-4 flex justify-end">
            <button
                class="btn text-sm"
                @click="isCriticalStockModalOpen = false"
            >
                {{ __('ui.close') }}
            </button>
        </div>

    </div>
</div>

<!-- Modal simples de adicionar estoque -->
<div
  x-show="isStockAddModalOpen"
  x-cloak
  class="fixed inset-0 z-50 flex items-center justify-center p-4"
  style="background-color: rgba(0,0,0,0.5);"
  @keydown.escape.window="isStockAddModalOpen=false"
>
  <div
    class="cosmic-card w-full max-w-md backdrop-blur-lg"
    @click.outside="isStockAddModalOpen=false"
  >
    <div class="flex items-center justify-between p-4 border-b border-[color:var(--border)]">
      <h2 class="text-lg font-semibold">{{ __('ui.add_stock') }}: <span x-text="stockProduct?.name ?? ''"></span></h2>
      <button @click="isStockAddModalOpen=false" class="p-1 rounded-full hover:bg-white/10">&times;</button>
    </div>

    <form @submit.prevent="
        if(stockQuantity > 0){
          fetch('/admin/products/' + stockProduct.id + '/add-stock', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            },
            body: JSON.stringify({ quantity: stockQuantity })
          })
          .then(res => res.json())
          .then(data => {
            if(data.success){
              alert('{{ __('ui.stock_added_success') }}');
              isStockAddModalOpen = false;
              location.reload(); // ou atualizar localmente
            } else {
              alert(data.message || 'Erro');
            }
          });
        } else {
          alert('{{ __('ui.enter_valid_quantity') }}');
        }
    ">
      <div class="p-6 space-y-4">
        <div>
          <label for="stock_quantity" class="block text-sm font-medium text-[color:var(--muted)]">{{ __('ui.stock_quantity') }}</label>
          <input
            type="number"
            id="stock_quantity"
            min="1"
            x-model.number="stockQuantity"
            placeholder="0"
            class="input-base mt-1 w-full"
            :class="theme==='theme-dark' ? 'input-dark' : 'input-light'"
            required
          >
        </div>
      </div>

      <div class="flex justify-end gap-3 p-4 border-t border-[color:var(--border)]"
           :class="theme==='theme-dark' ? 'bg-[rgba(2,6,23,.5)]' : 'bg-slate-50/50'">
        <button type="button" @click="isStockAddModalOpen=false" class="btn">{{ __('ui.cancel') }}</button>
        <button type="submit" class="btn btn-accent">{{ __('ui.add') }}</button>
      </div>
    </form>
  </div>
</div>



  <!-- MODAL: DELETE CONFIRMATION -->
  <div x-show="isDeleteModalOpen" style="display: none;" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0,0,0,0.5);">
    <div @click.outside="isDeleteModalOpen = false" x-show="isDeleteModalOpen" x-transition class="cosmic-card w-full max-w-sm backdrop-blur-lg p-6 text-center">
        <h3 class="text-lg font-semibold mb-2">{{ __('ui.confirm_delete_title') }}</h3>
        <p class="text-sm text-[color:var(--muted)]">{{ __('ui.confirm_delete_text') }}</p>
        <div class="mt-6 flex justify-center gap-4">
            <button @click="isDeleteModalOpen = false" type="button" class="btn">{{ __('ui.cancel') }}</button>
            <form :action="deletingProductUrl" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-lg text-white text-sm" style="background:#dc262a">{{ __('ui.confirm_delete') }}</button>
            </form>
        </div>
    </div>
  </div>

  <!-- MODAL: GERENCIAR ESTOQUE -->
<div
  x-show="isStockModalOpen"
  x-cloak
  class="fixed inset-0 z-50 flex items-center justify-center p-4"
  style="background-color: rgba(0,0,0,0.5);"
  @keydown.escape.window="isStockModalOpen=false"
>
  <div
    class="cosmic-card w-full max-w-3xl backdrop-blur-lg"
    @click.outside="isStockModalOpen=false"
    x-data="{
      discounts: [],
      init() {
        if (!this.discounts.length && Array.isArray(window.discounts)) {
          this.discounts = JSON.parse(JSON.stringify(window.discounts));
        }
      },
      addRow() { this.discounts.push({ min_quantity: '', discounted_price: '' }); },
      removeRow(i) { this.discounts.splice(i,1); },
      sanitizeNumber(v) { return (v ?? '').toString().replace(',', '.'); }
    }"
  >
    <div class="flex items-center justify-between p-4 border-b border-[color:var(--border)]">
      <h2 class="text-lg font-semibold">
        {{ __('ui.manage_stock') }}: <span x-text="stockingProduct?.name ?? ''"></span>
      </h2>
      <button @click="isStockModalOpen=false" class="p-1 rounded-full hover:bg-white/10">&times;</button>
    </div>

    <form :action="`/admin/stock/${stockingProduct.id}`" method="POST">
      @csrf

      <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Left -->
        <div class="space-y-4">
          <div>
            <label for="stock_quantity" class="block text-sm font-medium text-[color:var(--muted)]">{{ __('ui.stock_quantity') }}</label>
            <input type="number" id="stock_quantity" name="quantity"
                   :value="stockingProduct.stock ? stockingProduct.stock.quantity : 0"
                   min="0" required
                   class="input-base mt-1"
                   :class="theme==='theme-dark' ? 'input-dark' : 'input-light'">
          </div>

          <div>
            <label for="stock_alert_level" class="block text-sm font-medium text-[color:var(--muted)]">{{ __('ui.stock_alert_level') }}</label>
            <input type="number" id="stock_alert_level" name="stock_alert_level"
                   :value="stockingProduct.stock ? stockingProduct.stock.stock_alert_level : 0"
                   min="0" required
                   class="input-base mt-1"
                   :class="theme==='theme-dark' ? 'input-dark' : 'input-light'">
          </div>
        </div>

        <!-- Right -->
        <div class="space-y-4">
          <div>
            <label for="stock_price" class="block text-sm font-medium text-[color:var(--muted)]">{{ __('ui.selling_price') }} (€)</label>
            <input type="number" step="0.01" id="stock_price" name="price"
                   :value="stockingProduct.stock ? stockingProduct.stock.price : ''"
                   min="0" required
                   class="input-base mt-1"
                   :class="theme==='theme-dark' ? 'input-dark' : 'input-light'">
          </div>

          <div>
            <label for="stock_cost_price" class="block text-sm font-medium text-[color:var(--muted)]">{{ __('ui.cost_price') }} (€)</label>
            <input type="number" step="0.01" id="stock_cost_price" name="cost_price"
                   :value="stockingProduct.stock ? stockingProduct.stock.cost_price : ''"
                   min="0" required
                   class="input-base mt-1"
                   :class="theme==='theme-dark' ? 'input-dark' : 'input-light'">
          </div>
        </div>
      </div>


      <div class="p-4 text-xs text-[color:var(--muted)] text-center">
        {{ __('ui.save_quantity_tip') }}
      </div>

      <div class="flex justify-end gap-3 p-4 border-t border-[color:var(--border)]"
           :class="theme==='theme-dark' ? 'bg-[rgba(2,6,23,.5)]' : 'bg-slate-50/50'">
        <button type="button" @click="isStockModalOpen=false" class="btn">{{ __('ui.cancel') }}</button>
        <button type="submit" class="btn btn-accent">{{ __('ui.save_stock') }}</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL: UPDATE PRICE -->
<div
  x-show="isPriceModalOpen"
  x-cloak
  class="fixed inset-0 z-50 flex items-center justify-center p-4"
  style="background-color: rgba(0,0,0,0.5);"
  @keydown.escape.window="isPriceModalOpen=false"
>
  <div
    class="cosmic-card w-full max-w-md backdrop-blur-lg"
    @click.outside="isPriceModalOpen=false"
    x-data="{ priceValue: '' }"
  >
    <div class="flex items-center justify-between p-4 border-b border-[color:var(--border)]">
      <h2 class="text-lg font-semibold">
        {{ __('ui.update_price') }}: <span x-text="priceProduct?.name ?? ''"></span>
      </h2>
      <button @click="isPriceModalOpen=false" class="p-1 rounded-full hover:bg-white/10">&times;</button>
    </div>

    <form :action="`/admin/stock/${priceProduct.id}/price`" method="POST">
      @csrf

      <div class="p-6 space-y-4">
        <div>
          <label for="update_price_value" class="block text-sm font-medium text-[color:var(--muted)]">
            {{ __('ui.selling_price') }} (€)
          </label>
          <input
            type="number"
            step="0.01"
            id="update_price_value"
            name="price"
            x-model="priceValue"
            min="0"
            required
            class="input-base mt-1"
            :class="theme==='theme-dark' ? 'input-dark' : 'input-light'"
            placeholder="0.00"
          >
        </div>

        <p class="text-xs text-[color:var(--muted)]">
          {{ __('ui.update_price_hint') }}
        </p>
      </div>

      <div class="flex justify-end gap-3 p-4 border-t border-[color:var(--border)]"
           :class="theme==='theme-dark' ? 'bg-[rgba(2,6,23,.5)]' : 'bg-slate-50/50'">
        <button type="button" @click="isPriceModalOpen=false" class="btn">{{ __('ui.cancel') }}</button>
        <button type="submit" class="btn btn-accent">{{ __('ui.save_price') }}</button>
      </div>
    </form>
  </div>
</div>

  <footer class="py-6 text-center text-xs" style="color:var(--muted);">
    © {{ date('Y') }} Cosmic Bar · Admin
  </footer>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    // 🔹 Resultado Financeiro
    new Chart(document.getElementById('resultChart'), {
        type: 'bar',
        data: {
            labels: ['Hoje', 'Semana', 'Mês'],
            datasets: [{
                label: 'Resultado (€)',
                data: [
                    {{ $financeStats['diario']->resultado }},
                    {{ $financeStats['semanal']->resultado }},
                    {{ $financeStats['mensal']->resultado }}
                ],
                backgroundColor: ['#22c55e', '#a855f7', '#38bdf8']
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } }
        }
    });

    // 🔹 Vendas x CMV x Despesas
    new Chart(document.getElementById('financeChart'), {
        type: 'bar',
        data: {
            labels: ['Vendas', 'CMV', 'Despesas'],
            datasets: [{
                data: [
                    {{ $financeStats['mensal']->vendas }},
                    {{ $financeStats['mensal']->cmv }},
                    {{ $financeStats['mensal']->despesas }}
                ],
                backgroundColor: ['#22c55e', '#f97316', '#ef4444']
            }]
        },
        options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom',
            labels: {
                color: '#cbd5f5',
                font: { size: 11 }
            }
        }
    }
}

    });

    // 🔹 Despesas por Categoria
    new Chart(document.getElementById('expenseCategoryChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($expensesMonth->groupBy('category')->keys()) !!},
            datasets: [{
                data: {!! json_encode(
                    $expensesMonth->groupBy('category')->map->sum('amount')->values()
                ) !!},
                backgroundColor: [
                    '#ef4444', '#f97316', '#eab308',
                    '#22c55e', '#06b6d4', '#6366f1'
                ]
            }]
        },
        options: {
            responsive: true
        }
    });

});
</script>

</body>
</html>

