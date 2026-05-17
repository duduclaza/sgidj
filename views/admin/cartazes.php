<div class="flex flex-col items-center py-8 px-4">
    <!-- Poster único -->
    <div id="poster-principal" class="relative mx-auto w-[210mm] min-h-[297mm] bg-white shadow-2xl overflow-hidden flex flex-col border border-slate-100 rounded-3xl" style="transform: scale(0.55); transform-origin: top center; margin-bottom: -120mm;">
        <div class="absolute top-0 right-0 p-20 opacity-10">
            <i data-lucide="lightbulb" class="w-64 h-64 text-indigo-600"></i>
        </div>

        <div class="p-20 flex-1 flex flex-col items-center text-center">
            <div class="bg-indigo-600 text-white px-8 py-3 rounded-full text-2xl font-black uppercase tracking-widest mb-10">
                Melhoria Contínua
            </div>

            <h1 class="text-7xl font-black text-slate-900 leading-tight mb-8">
                Sua ideia pode <span class="text-indigo-600 underline">transformar</span> a empresa! 💡
            </h1>

            <p class="text-3xl text-slate-500 font-medium mb-6 leading-relaxed">
                Preencha o formulário com o plano de ação e envie sua proposta de melhoria.
            </p>

            <div class="w-full mb-6 border border-indigo-100 rounded-2xl bg-indigo-50/50 px-10 py-6 text-left">
                <p class="text-2xl font-bold text-indigo-900 mb-4 uppercase tracking-wide">O formulário contém:</p>
                <ul class="space-y-3 text-2xl text-slate-700">
                    <li class="flex items-center gap-3"><span class="text-indigo-500 font-black">✓</span> Dados do Projeto</li>
                    <li class="flex items-center gap-3"><span class="text-indigo-500 font-black">✓</span> Plano de Ação 5W2H</li>
                </ul>
            </div>

            <div class="bg-slate-50 p-12 rounded-[3rem] border-4 border-dashed border-slate-200 mb-10">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=<?= urlencode(url('/solicitar')) ?>" class="w-80 h-80">
            </div>

            <p class="text-3xl font-bold text-slate-900 mb-3">Aponte a câmera do seu celular</p>
            <p class="text-xl text-slate-400 uppercase tracking-widest font-black">E transforme a nossa empresa</p>
        </div>

        <div class="bg-slate-900 p-12 text-center">
            <p class="text-white text-2xl font-bold opacity-80"><?= e(config('app.name')) ?> - Gestão da Qualidade</p>
        </div>
    </div>
</div>

<div class="mt-4 flex justify-center gap-6 pb-20">
    <button onclick="window.print()" class="btn-primary !px-10 !py-4 text-lg">
        <i data-lucide="printer" class="w-6 h-6"></i> Imprimir Cartaz
    </button>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    #poster-principal, #poster-principal * { visibility: visible; }
    #poster-principal { position: absolute; left: 0; top: 0; transform: scale(1); margin: 0; width: 210mm; min-height: 297mm; border-radius: 0; }
}
</style>
