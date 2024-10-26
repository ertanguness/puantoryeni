<?php

require_once "App/Helper/helper.php";

use App\Helper\Helper;
$selected_firm = $case->firm_id ?? $_SESSION['firm_id'];

?>


<form action="" id="caseForm">
    <div class="row">
        <div class="col-md-4">
            <input type="text" name="id" id="id" class="form-control" value="<?php echo $new_id ?>">
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
            <?php echo $company->myCompanySelect("firm_company", $selected_firm ,"disabled" ); ?>
        </div>
        <div class="col-md-2">
            <label for="case_name" class="form-label">Kasa Adı</label>
        </div>
        <div class="col-md-4">
            <input type="text" name="case_name" id="case_name" class="form-control"
                value="<?php echo $case->case_name ?? '' ?>">
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
    <div class="row mb-3">
        <div class="col-md-2">
            <label for="case_name" class="form-label">Kasa Para Birimi</label>
        </div>
        <div class="col-md-4">
            <?php echo Helper::moneySelect('case_money_unit', $case->start_budget_money ?? ''); ?>
        </div>
    </div>
</form>