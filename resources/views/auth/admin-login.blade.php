<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="h-full">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Cosmic Bar · {{ __('auth.title') }}</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .stars{
      background:
       radial-gradient(2px 2px at 20% 30%, #fff8 50%, transparent 51%) 0 0/120px 120px,
       radial-gradient(1.5px 1.5px at 60% 70%, #fff6 50%, transparent 51%) 0 0/100px 100px,
       radial-gradient(1px 1px at 80% 20%, #fff5 50%, transparent 51%) 0 0/140px 140px,
       #0f0b17;
      animation: tw 8s linear infinite;
    }
    @keyframes tw { to { background-position: 120px 120px,100px 100px,140px 140px; } }
  </style>
</head>
<body class="h-full stars text-slate-100">
  <div class="min-h-full flex items-center justify-center p-6">
    <div class="w-full max-w-md bg-slate-900/70 rounded-2xl shadow-2xl ring-1 ring-white/10 backdrop-blur p-8">
      <div class="flex items-center justify-between mb-6">
        <div>
          <h1 class="text-xl font-semibold">COSMIC BAR</h1>
          <p class="text-xs text-slate-300">{{ __('auth.admin_area') }}</p>
        </div>
        <div class="flex gap-2">
          <a href="{{ route('locale.set','it') }}"
             class="px-2 py-1 text-xs rounded {{ app()->getLocale()==='it'?'bg-white text-slate-900':'bg-slate-800' }}">IT</a>
              <a href="{{ route('locale.set','en') }}"
             class="px-2 py-1 text-xs rounded {{ app()->getLocale()==='en'?'bg-white text-slate-900':'bg-slate-800' }}">EN</a>
              <a href="{{ route('locale.set','es') }}"
             class="px-2 py-1 text-xs rounded {{ app()->getLocale()==='es'?'bg-white text-slate-900':'bg-slate-800' }}">ES</a>
        </div>
      </div>

      <h2 class="text-lg font-medium mb-1">{{ __('auth.title') }}</h2>
      <p class="text-sm text-slate-300 mb-6">{{ __('auth.subtitle') }}</p>

      <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
        @csrf
       {{-- Username --}}
<div>
  <label for="username" class="block text-sm mb-1">{{ __('auth.username') }}</label>
  <input id="username" name="username" type="text" value="{{ old('username') }}" required autofocus
         class="w-full rounded-xl bg-slate-900/50 border border-white/10 px-3 py-3 focus:ring-2 focus:ring-sky-400">
  @error('username') <p class="text-rose-400 text-sm mt-1">{{ $message }}</p> @enderror
</div>


        <div>
          <label for="password" class="block text-sm mb-1">{{ __('auth.password') }}</label>
          <input id="password" name="password" type="password" required
                 class="w-full rounded-xl bg-slate-900/50 border border-white/10 px-3 py-3 focus:ring-2 focus:ring-sky-400">
          @error('password') <p class="text-rose-400 text-sm mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
          <label class="inline-flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember" class="rounded bg-slate-900/50 border-white/20">
            {{ __('auth.remember') }}
          </label>
          <span class="text-xs text-slate-400">Admin v1.0</span>
        </div>

        <button type="submit"
                class="w-full rounded-xl px-4 py-3 font-semibold bg-gradient-to-r from-amber-300 to-sky-400 text-slate-900 hover:opacity-90">
          {{ __('auth.sign_in') }}
        </button>
      </form>

      @if ($errors->any())
        <div class="mt-4 text-sm text-rose-300">{{ __('auth.check_creds') }}</div>
      @endif

      <div class="mt-6 text-center text-xs text-slate-400">
        © {{ date('Y') }} Cosmic Bar
      </div>
    </div>
  </div>
</body>
</html>
