<form action="" id="caseForm">
                            <div class="row d-none">
                                <div class="col-md-4">
                                    <input type="text" name="id" class="form-control" value="<?php echo $case->id ?? 0 ?>">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="action" value="saveCase" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="case_name" class="form-label">Firması</label>
                                </div>
                                <div class="col-md-4">
                                    <?php echo $company->myCompanySelect("firm_company", $case->company_id ?? ''); ?>
                                </div>
                                <div class="col-md-2">
                                    <label for="case_name" class="form-label">Kasa Adı</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="case_name" id="case_name" class="form-control" value="<?php echo $case->case_name ?? '' ?>">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="case_name" class="form-label">Bankası</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="bank_name" class="form-control" value="<?php echo $case->bank_name ?? '' ?>">

                                </div>
                                <div class="col-md-2">
                                    <label for="case_name" class="form-label">Şubesi</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="branch_name" class="form-control" value="<?php echo $case->branch_name ?? '' ?>">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="case_name" class="form-label">Başlangıç Bütçesi</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="start_budget" class="form-control" value="<?php echo $case->start_budget ?? '' ?>">
                                </div>
                                <div class="col-md-2">
                                    <label for="case_name" class="form-label">Açıklama</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" name="description" class="form-control" value="<?php echo $case->description ?? '' ?>">
                                </div>
                            </div>
                        </form>