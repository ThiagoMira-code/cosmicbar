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
    tab: 'products',
    isNewModalOpen: false,
    isEditModalOpen: false,
    isDeleteModalOpen: false,
     isStockModalOpen: false,    
    editingProduct: {},
    stockingProduct: {}, 
      isPriceModalOpen: false,       // <— NOVO
  priceProduct: {},              // <— NOVO
    deletingProductUrl: '',
    theme: localStorage.getItem('cosmic-theme') || 'theme-dark',
    init(){
        document.documentElement.classList.add(this.theme);
        if (window.shouldOpenModal) { this.isNewModalOpen = true; }
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
        <a href="{{ route('locale.set','en') }}"
            class="px-2 py-1 text-xs rounded transition flex items-center gap-1
                    {{ app()->getLocale()==='en'
                        ? 'bg-white text-slate-900'
                        : 'bg-[rgba(2,6,23,.5)] text-[color:var(--text)] border border-[color:var(--border)] hover:bg-[rgba(2,6,23,.65)]' }}">
            <svg class="h-3.5 w-5" viewBox="0 0 60 30" aria-hidden="true"><path fill="#012169" d="M0 0h60v30H0z"/><path fill="#FFF" d="M0 0l60 30m0-30L0 30"/><path stroke="#C8102E" stroke-width="5" d="M0 0l60 30m0-30L0 30"/><path fill="#FFF" d="M30 0v30M0 15h60"/><path stroke="#C8102E" stroke-width="7" d="M30 0v30M0 15h60"/></svg>
            EN
        </a>

        <a href="{{ route('locale.set','it') }}"
            class="px-2 py-1 text-xs rounded transition flex items-center gap-1
                    {{ app()->getLocale()==='it'
                        ? 'bg-white text-slate-900'
                        : 'bg-[rgba(2,6,23,.5)] text-[color:var(--text)] border border-[color:var(--border)] hover:bg-[rgba(2,6,23,.65)]' }}">
            <svg class="h-3.5 w-5" viewBox="0 0 3 2" aria-hidden="true"><path fill="#008C45" d="M0 0h1v2H0z"/><path fill="#F4F5F0" d="M1 0h1v2H1z"/><path fill="#CD212A" d="M2 0h1v2H2z"/></svg>
            IT
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

    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition class="p-4 rounded-xl text-green-900 flex items-center justify-between" style="background-color: var(--chip-ok-bg); border: 1px solid var(--chip-ok-bd);">
            <p>{{ session('success') }}</p>
            <button @click="show = false" class="text-xl">&times;</button>
        </div>
    @endif
@if(auth()->user()->is_admin)
    <section x-show="tab==='products'" x-transition>
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
        <input type="text" name="search" value="{{ request('search') }}" placeholder="{{ __('ui.search_sku') }}"
               class="w-full md:w-72 px-3 py-2 rounded-xl border border-[color:var(--border)]"
               :class="theme==='theme-dark' ? 'bg-[rgba(2,6,23,.5)]' : 'bg-white/70'">
        <select name="category_id" class="px-3 py-2 rounded-xl border border-[color:var(--border)]" :class="theme==='theme-dark' ? 'bg-[rgba(2,6,23,.5)]' : 'bg-white/70'" onchange="this.form.submit()">
            <option value="">{{ __('ui.all_categories') }}</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @if(request('category_id') == $category->id) selected @endif>{{ $category->name }}</option>
            @endforeach
        </select>
        <button @click="isNewModalOpen = true" type="button" class="px-4 py-2 rounded-xl btn-accent">
          {{ __('ui.new_product') }}
        </button>
      </form>

      <!-- PRODUCT TABLE -->
      <div class="mt-4 cosmic-card overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full" style="background:transparent">
            <thead class="text-left text-sm border-b border-[color:var(--border)] text-[color:var(--muted)]">
                <!-- Table headers -->
                <tr><th class="px-4 py-3">{{__('ui.product')}}</th><th class="px-4 py-3">{{__('ui.sku')}}</th><th class="px-4 py-3">{{__('ui.category')}}</th><th class="px-4 py-3">{{__('ui.status')}}</th><th class="px-4 py-3 text-right">{{__('ui.actions')}}</th></tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                <tr class="border-t border-[color:var(--border)]" :class="theme==='theme-light' ? 'hover:bg-black/5' : 'hover:bg-white/5'">
                    <td class="px-4 py-3 font-medium whitespace-nowrap">{{ $product->name }}</td>
                    <td class="px-4 py-3 whitespace-nowrap">{{ Str::upper(Str::substr($product->category->name, 0, 4)) .'-'. str_pad($product->id, 3, '0', STR_PAD_LEFT) }}</td>
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
                <tr class="border-t border-[color:var(--border)]"><td colspan="5" class="text-center py-6 text-[color:var(--muted)]">{{ __('ui.no_products_found') }}</td></tr>
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
    <div class="cosmic-card p-4"><div class="text-sm text-[color:var(--muted)]">{{ __('ui.items_stock') }}</div><div class="mt-2 text-2xl font-semibold">{{ number_format($itemsInStock, 0, ',', '.') }}</div></div>
    <div class="cosmic-card p-4"><div class="text-sm text-[color:var(--muted)]">{{ __('ui.reorder_need') }}</div><div class="mt-2 text-2xl font-semibold">{{ $lowStockAlerts }}</div></div>
    <div class="cosmic-card p-4"><div class="text-sm text-[color:var(--muted)]">{{ __('ui.stock_value') }}</div><div class="mt-2 text-2xl font-semibold">€{{ number_format($stockValue, 2, ',', '.') }}</div></div>
    <div class="cosmic-card p-4"><div class="text-sm text-[color:var(--muted)]">{{ __('ui.days_cover') }}</div><div class="mt-2 text-2xl font-semibold">N/A</div></div>
  </div>

  @if (session('low_stock'))
    <div class="mt-4 p-3 rounded-lg border" style="background-color: var(--chip-warn-bg); border-color: var(--chip-warn-bd); color:#fde68a;">
      {{ session('low_stock') }}
    </div>
  @endif

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

    <!-- NOVO: Update Price -->
    <button
      class="btn text-sm"
      @click="
        priceProduct = @js($product->load('stock'));
        priceValue = priceProduct?.stock?.price ?? '';
        isPriceModalOpen = true;
      "
    >{{ __('ui.update_price') }}</button>
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

    mesas: JSON.parse(localStorage.getItem('mesas_pdv')) || {
      1: [], 2: [], 3: [], 4: [], 5: [],
      6: [], 7: [], 8: [], 9: [], 10: []
    },

    save(){
      localStorage.setItem('mesas_pdv', JSON.stringify(this.mesas));
    },

    addToCart(product){
      const cart = this.mesas[this.mesaAtiva];
      const item = cart.find(i => i.id === product.id);

      if(item){
        item.qty++;
      } else {
        cart.push({ ...product, qty: 1 });
      }

      this.save();
    },

    removeItem(id){
      this.mesas[this.mesaAtiva] =
        this.mesas[this.mesaAtiva].filter(i => i.id !== id);

      this.save();
    },

    total(){
      return this.mesas[this.mesaAtiva]
        .reduce((t,i) => t + (i.price * i.qty), 0);
    },

    async checkout(){
      if(!this.mesas[this.mesaAtiva].length){
        alert('Mesa vazia');
        return;
      }

      if(!confirm('Pagamento já foi realizado?')){
        return;
      }

      try {
        const response = await fetch('/admin/sales/checkout', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document
              .querySelector('meta[name=csrf-token]').content
          },
          body: JSON.stringify({
            mesa: this.mesaAtiva,
            itens: this.mesas[this.mesaAtiva],
            total: this.total()
          })
        });

        if(!response.ok){
          throw new Error('Erro ao salvar venda');
        }

        // limpa mesa SOMENTE após salvar no banco
        this.mesas[this.mesaAtiva] = [];
        this.save();

        alert('Venda finalizada com sucesso!');
      } catch(e){
        alert('Erro ao finalizar venda');
        console.error(e);
      }
    }
  }"
  class="space-y-4"
>

  <!-- TABLES -->
  <div class="flex gap-2 flex-wrap">
    <template x-for="n in 10" :key="n">
      <button
        class="cosmic-card px-4 py-2 text-sm"
        :class="mesaAtiva === n ? 'ring-2 ring-accent' : ''"
        @click="mesaAtiva = n"
      >
        {{ __('ui.table') }} <span x-text="n"></span>
      </button>
    </template>
  </div>

  <!-- MAIN GRID -->
  <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

    <!-- PRODUCTS -->
    <div class="lg:col-span-3">
      <div class="grid sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($products as $product)
        <div class="cosmic-card p-3 flex flex-col">
          <img
            src="{{ $product->photo_url ?? '/img/placeholder.png' }}"
            class="h-28 w-full object-cover rounded-lg mb-3"
          >

          <div class="flex-1">
            <h3 class="font-semibold text-sm">{{ $product->name }}</h3>
            <p class="text-xs text-[color:var(--muted)]">
              {{ $product->category->name }}
            </p>
          </div>

          <div class="mt-3 flex items-center justify-between">
            <span class="font-semibold text-lg">
              €{{ number_format($product->stock->price ?? 0, 2, ',', '.') }}
            </span>

            <button
              class="btn btn-accent text-sm"
              @click="addToCart({
                id: {{ $product->id }},
                name: '{{ $product->name }}',
                price: {{ $product->stock->price ?? 0 }}
              })"
            >
              {{ __('ui.add') }}
            </button>
          </div>
        </div>
        @endforeach
      </div>
    </div>

    <!-- ORDER -->
    <div class="cosmic-card p-4 flex flex-col">
      <h2 class="font-semibold mb-4">
        {{ __('ui.order') }} – {{ __('ui.table') }}
        <span x-text="mesaAtiva"></span>
      </h2>

      <div class="flex-1 space-y-3 overflow-auto">
        <template x-if="!mesas[mesaAtiva].length">
          <p class="text-sm text-[color:var(--muted)]">
            {{ __('ui.empty_table') }}
          </p>
        </template>

        <template x-for="item in mesas[mesaAtiva]" :key="item.id">
          <div class="flex justify-between items-center text-sm">
            <div>
              <p x-text="item.name"></p>
              <span class="text-xs text-[color:var(--muted)]">
                €<span x-text="item.price.toFixed(2)"></span> ×
                <span x-text="item.qty"></span>
              </span>
            </div>

            <button
              class="text-red-500 text-xs"
              @click="removeItem(item.id)"
            >
              ✕
            </button>
          </div>
        </template>
      </div>

      <div class="border-t border-[color:var(--border)] mt-4 pt-4">
        <div class="flex justify-between font-semibold">
          <span>{{ __('ui.total') }}</span>
          <span>€<span x-text="total().toFixed(2)"></span></span>
        </div>

        <button
          class="btn btn-accent w-full mt-4"
          @click="checkout()"
        >
          {{ __('ui.checkout') }}
        </button>
      </div>
    </div>

  </div>
</section>

@if(auth()->user()->is_admin)
<section x-show="tab === 'finance'" x-transition x-cloak class="space-y-6" x-data="{ showDetailedList: false }">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-white">{{ __('ui.finance_details') }}</h2>
        
        <button @click="showDetailedList = !showDetailedList" 
                class="text-xs bg-accent/20 hover:bg-accent/40 text-accent px-3 py-1 rounded-lg border border-accent/30 transition">
            <span x-text="showDetailedList ? '{{ __('ui.hide_details') }}' : '{{ __('ui.view_details') }}'"></span>
        </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="cosmic-card p-6 border-l-4 border-blue-500">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-[color:var(--muted)]">{{ __('ui.today') }}</h3>
            <div class="mt-4">
                <p class="text-2xl font-bold">€{{ number_format($financeStats['diario']->lucro_liquido ?? 0, 2, ',', '.') }}</p>
                <p class="text-xs text-green-400 mt-1">{{ __('ui.sales') }}: €{{ number_format($financeStats['diario']->total_vendas ?? 0, 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="cosmic-card p-6 border-l-4 border-purple-500">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-[color:var(--muted)]">{{ __('ui.this_week') }}</h3>
            <div class="mt-4">
                <p class="text-2xl font-bold">€{{ number_format($financeStats['semanal']->lucro_liquido ?? 0, 2, ',', '.') }}</p>
                <p class="text-xs text-green-400 mt-1">{{ __('ui.sales') }}: €{{ number_format($financeStats['semanal']->total_vendas ?? 0, 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="cosmic-card p-6 border-l-4 border-accent">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-[color:var(--muted)]">{{ __('ui.this_month') }}</h3>
            <div class="mt-4">
                <p class="text-2xl font-bold">€{{ number_format($financeStats['mensal']->lucro_liquido ?? 0, 2, ',', '.') }}</p>
                <p class="text-xs text-green-400 mt-1">{{ __('ui.sales') }}: €{{ number_format($financeStats['mensal']->total_vendas ?? 0, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div x-show="showDetailedList" x-transition class="cosmic-card p-4 bg-white/5 border border-white/10">
        <h3 class="text-white font-semibold mb-3">{{ __('ui.recent_transactions') }}</h3>
        <p class="text-[color:var(--muted)] text-sm italic">
            {{ __('ui.detailed_report_placeholder') }}
        </p>
        </div>

    <div class="cosmic-card p-4">
        <h2 class="font-semibold mb-4 text-lg">{{ __('ui.stock_investment_summary') }}</h2>
        <div class="text-sm text-[color:var(--muted)]">
            <p>{{ __('ui.total_invested_stock') }}: 
                <span class="text-white font-mono">
                    €{{ number_format($stockValue ?? 0, 2, ',', '.') }}
                </span>
            </p>
        </div>
    </div>
</section>
@endif
  </main>

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
  <div x-show="isEditModalOpen" style="display: none;" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0,0,0,0.5);">
    <div @click.outside="isEditModalOpen = false" x-show="isEditModalOpen" x-transition class="cosmic-card w-full max-w-lg backdrop-blur-lg">
        <div class="flex items-center justify-between p-4 border-b border-[color:var(--border)]">
            <h2 class="text-lg font-semibold">{{ __('ui.edit_product') }}</h2>
            <button @click="isEditModalOpen = false" class="p-1 rounded-full hover:bg-white/10">&times;</button>
        </div>
        <form :action="`/admin/products/${editingProduct.id}`" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div><label for="edit_product_name" class="block text-sm font-medium text-[color:var(--muted)]">{{ __('ui.product_name') }}</label><input type="text" id="edit_product_name" name="name" x-model="editingProduct.name" required class="input-base mt-1" :class="theme==='theme-dark' ? 'input-dark' : 'input-light'"></div>
                <div><label for="edit_product_category" class="block text-sm font-medium text-[color:var(--muted)]">{{ __('ui.category') }}</label><select id="edit_product_category" name="category_id" x-model="editingProduct.category_id" required class="input-base mt-1" :class="theme==='theme-dark' ? 'input-dark' : 'input-light'"><option value="">{{ __('ui.select_category') }}</option>@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></div>
            </div>
            <div class="flex justify-end gap-3 p-4 border-t border-[color:var(--border)]" :class="theme==='theme-dark' ? 'bg-[rgba(2,6,23,.5)]' : 'bg-slate-50/50'"><button type="button" @click="isEditModalOpen = false" class="btn">{{ __('ui.cancel') }}</button><button type="submit" class="btn btn-accent">{{ __('ui.update_product') }}</button></div>
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

  <!-- MODAL: EDIT PRODUCT -->
  <div x-show="isEditModalOpen" style="display: none;" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background-color: rgba(0,0,0,0.5);">
    <div @click.outside="isEditModalOpen = false" x-show="isEditModalOpen" x-transition class="cosmic-card w-full max-w-lg backdrop-blur-lg">
        <div class="flex items-center justify-between p-4 border-b border-[color:var(--border)]">
            <h2 class="text-lg font-semibold">{{ __('ui.edit_product') }}</h2>
            <button @click="isEditModalOpen = false" class="p-1 rounded-full hover:bg-white/10">&times;</button>
        </div>
        <form :action="`/admin/products/${editingProduct.id}`" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div><label for="edit_product_name" class="block text-sm font-medium text-[color:var(--muted)]">{{ __('ui.product_name') }}</label><input type="text" id="edit_product_name" name="name" x-model="editingProduct.name" required class="input-base mt-1" :class="theme==='theme-dark' ? 'input-dark' : 'input-light'"></div>
                <div><label for="edit_product_category" class="block text-sm font-medium text-[color:var(--muted)]">{{ __('ui.category') }}</label><select id="edit_product_category" name="category_id" x-model="editingProduct.category_id" required class="input-base mt-1" :class="theme==='theme-dark' ? 'input-dark' : 'input-light'"><option value="">{{ __('ui.select_category') }}</option>@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select></div>
                <div><label for="edit_product_status" class="block text-sm font-medium text-[color:var(--muted)]">{{ __('ui.status') }}</label><select id="edit_product_status" name="status" x-model="editingProduct.status" required class="input-base mt-1" :class="theme==='theme-dark' ? 'input-dark' : 'input-light'"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
            </div>
            <div class="flex justify-end gap-3 p-4 border-t border-[color:var(--border)]" :class="theme==='theme-dark' ? 'bg-[rgba(2,6,23,.5)]' : 'bg-slate-50/50'"><button type="button" @click="isEditModalOpen = false" class="btn">{{ __('ui.cancel') }}</button><button type="submit" class="btn btn-accent">{{ __('ui.update_product') }}</button></div>
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

      <!-- Quantity discounts -->
      <div class="px-6">
        <div class="flex items-center justify-between mb-2">
          <h3 class="text-base font-semibold">{{ __('ui.quantity_discounts_optional') }}</h3>
          <button type="button" class="btn text-sm" @click="addRow()">+ {{ __('ui.add_tier') }}</button>
        </div>

        <div class="overflow-x-auto border border-[color:var(--border)] rounded-xl">
          <table class="min-w-full" style="background:transparent">
            <thead class="text-left text-sm border-b border-[color:var(--border)] text-[color:var(--muted)]">
              <tr>
                <th class="px-4 py-3 w-1/3">{{ __('ui.min_quantity') }}</th>
                <th class="px-4 py-3 w-1/3">{{ __('ui.unit_price') }} (€)</th>
                <th class="px-4 py-3 w-1/3 text-right">{{ __('ui.remove') }}</th>
              </tr>
            </thead>
            <tbody>
              <template x-if="!discounts.length">
                <tr>
                  <td colspan="3" class="px-4 py-6 text-center text-[color:var(--muted)]">
                    {{ __('ui.no_discount_rows_hint') }}
                  </td>
                </tr>
              </template>

              <template x-for="(row, i) in discounts" :key="i">
                <tr class="border-t border-[color:var(--border)]">
                  <td class="px-4 py-3">
                    <input type="number" min="1" class="input-base w-full"
                           :class="theme==='theme-dark' ? 'input-dark' : 'input-light'"
                           x-model="row.min_quantity"
                           :name="`min_quantity[${i}]`"
                           :placeholder="__('ui.example_qty')">
                  </td>
                  <td class="px-4 py-3">
                    <input type="number" min="0" step="0.01" class="input-base w-full"
                           :class="theme==='theme-dark' ? 'input-dark' : 'input-light'"
                           x-model="row.discounted_price"
                           :name="`discount_price[${i}]`"
                           :placeholder="__('ui.example_price')">
                  </td>
                  <td class="px-4 py-3 text-right">
                    <button class="btn text-sm" type="button" @click="removeRow(i)">{{ __('ui.remove') }}</button>
                  </td>
                </tr>
              </template>
            </tbody>
          </table>
        </div>

        <p class="mt-3 text-xs text-[color:var(--muted)]">
          {{ __('ui.discount_apply_hint') }}
        </p>
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

</body>
</html>

