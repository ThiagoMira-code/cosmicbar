<div class="flex gap-2">
  <a href="{{ route('locale.set','en') }}"
     class="px-2 py-1 text-xs rounded transition
            {{ app()->getLocale()==='en' ? 'bg-white text-slate-900' : 'bg-slate-800 text-slate-200 hover:bg-slate-700' }}">
     EN
  </a>
  <a href="{{ route('locale.set','it') }}"
     class="px-2 py-1 text-xs rounded transition
            {{ app()->getLocale()==='it' ? 'bg-white text-slate-900' : 'bg-slate-800 text-slate-200 hover:bg-slate-700' }}">
     IT
  </a>
</div>
