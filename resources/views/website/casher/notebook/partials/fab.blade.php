    @if(auth('casher')->user()->hasAbility('notebook_create'))
    <!-- Add Customer FAB -->
    <button @click="openAddCustomer()" class="fixed bottom-6 right-1/2 translate-x-1/2 sm:translate-x-0 sm:right-6 bg-primary hover:bg-emerald-600 text-white w-16 h-16 rounded-2xl shadow-[0_8px_30px_rgb(16,185,129,0.4)] flex items-center justify-center transition-all hover:-translate-y-1 active:scale-95 z-20 focus:outline-none focus:ring-4 focus:ring-emerald-500/30 group">
        <i class="ph-bold ph-plus text-3xl group-hover:rotate-90 transition-transform duration-300"></i>
    </button>
    @endif
