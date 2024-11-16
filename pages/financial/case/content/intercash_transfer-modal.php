<div class="modal modal-blur fade" id="intercash_transfer-modal" tabindex="-1" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kasalar arası Transfer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body ">
                <form action="" id="caseTransferForm">
                    <input type="hidden" name="from_case" id="from_case" value="0">
                    <div class="card mb-3">
                        <div class="ribbon ribbon-top bg-yellow">
                            <!-- Download SVG icon from http://tabler-icons.io/i/star -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path
                                    d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                </path>
                            </svg>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">Lütfen kasalar arasında para transferi yaparken bilgilerinizi
                                dikkatlice
                                kontrol ediniz.</h3>
                            <p class="text-secondary m-0"><strong>Hedef Kasa:</strong> Paranın transfer edileceği kasayı
                                seçiniz.</p>
                            <p class="text-secondary m-0"><strong>Tutar:</strong> Transfer edilecek miktarı giriniz.</p>
                            <p class="text-secondary m-0"><strong>Açıklama:</strong> Transfer işlemiyle ilgili detaylı
                                bir
                                açıklama ekleyiniz (isteğe bağlı).</p>
                        </div>
                    </div>
                    <div class="row mb-3 align-items-end">

                        <div class="col">
                            <label class="form-label">Aktarılacak Kasa</label>
                            <select name="to_case" id="to_case" class="form-control select2" style="width:100%">
                                <option value="0">Kasa Seçiniz!</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="form-label">Aktarılacak Tutar</label>
                        <input type="text" class="form-control money" name="amount" id="amount"
                            placeholder="Tutar giriniz">
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Açıklama</label>
                        <textarea class="form-control" name="description" id="description" rows="3"
                            style="min-height: 100px" placeholder="Açıklama"></textarea>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn me-auto" data-bs-dismiss="modal">Çık</button>
                <button type="button" class="btn btn-primary" id="add-case-transfer" >Transfer
                    Yap</button>
            </div>
        </div>
    </div>
</div>