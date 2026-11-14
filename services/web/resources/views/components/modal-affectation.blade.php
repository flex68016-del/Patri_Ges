@props(['open' => false])

<div x-data="{ open: $open }" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="open = false"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="inline-block align-bottom bg-white rounded-md text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-xl font-semibold text-[#1f2937] mb-6" id="modal-title">Affecter un bien</h3>

                        <form class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-[#374151] mb-2">Bien</label>
                                <input type="text" value="TG-VEH-2026-000001" disabled class="w-full px-4 py-3 border border-[#e5e7eb] rounded-md bg-gray-50 text-[#6b7280]">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#374151] mb-2">Service destinataire</label>
                                <select class="w-full px-4 py-3 border border-[#e5e7eb] rounded-md focus:ring-2 focus:ring-[#047857] focus:border-[#047857]">
                                    <option>Ministère</option>
                                    <option>Direction</option>
                                    <option>Service</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#374151] mb-2">Date d'affectation</label>
                                <input type="date" class="w-full px-4 py-3 border border-[#e5e7eb] rounded-md focus:ring-2 focus:ring-[#047857] focus:border-[#047857]">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#374151] mb-2">Motif</label>
                                <textarea class="w-full px-4 py-3 border border-[#e5e7eb] rounded-md focus:ring-2 focus:ring-[#047857] focus:border-[#047857]" rows="3"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-[#374151] mb-2">Référence de l'acte</label>
                                <input type="text" class="w-full px-4 py-3 border border-[#e5e7eb] rounded-md focus:ring-2 focus:ring-[#047857] focus:border-[#047857]" placeholder="N° d'arrêté ou de décision">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-[#047857] text-base font-medium text-white hover:bg-[#065f46] focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    Affecter
                </button>
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-[#6b7280] hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" @click="open = false">
                    Annuler
                </button>
            </div>
        </div>
    </div>
</div>
