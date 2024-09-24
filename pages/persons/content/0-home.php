<?php
require_once "App/Helper/jobs.php";

$jobGroups = new Jobs();

if(isset($person->wage_type) && $person->wage_type == 1){
    $wage_type_label = 'Aylık Maaş';
    $white_checked = 'checked' ;
}else{
    $wage_type_label = 'Günlük Ücret';
    $blue_checked = 'checked' ;
}
?>

<form action="" id="personForm">

    <div class="row d-none">
        <div class="col-4">
            <input type="text" class="form-control" name="id" value="<?php echo $person->id ?? 0; ?>" required>
        </div>
        <div class="col-4">
            <input type="text" class="form-control" name="action" value="savePerson" required>
        </div>
        <div class="col-4">
            <input type="text" class="form-control" name="firm_id" value="<?php echo $firm_id ?? '' ?>" required>
        </div>

    </div>
    <div class="row mt-2">
        <div class="col-md-2">
            <label for="">Adı Soyadı (*)</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="full_name" value="<?php echo $person->full_name ?? ''; ?>"
                required>
        </div>
        <div class="col-md-2 mt-2">
            <label for="">Tc Kimlik No (*)</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="kimlik_no" value="<?php echo $person->kimlik_no ?? ''; ?>"
                required>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-2">
            <label for="">İşe Başlama Tarihi (*)</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control flatpickr" name="job_start_date"
                value="<?php echo $person->job_start_date ?? ''; ?>" required>
        </div>
        <div class="col-md-2 mt-2">
            <label id="wage_type_label" for=""><?php echo $wage_type_label; ?></label>
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control fw-bold" name="daily_wages"
                value="<?php echo $person->daily_wages ?? ''; ?>">
        </div>
        <div class="col-md-2 mt-2">

            <div class="d-flex">

                <label class="form-check form-check-inline">
                    <input class="form-check-input wage_type" type="radio" value="2" name="wage_type" id="blue_collar"
                        <?php echo $blue_checked ?? ''; ?> >
                    <span class="form-check-label">Mavi Yaka</span>
                </label>
                <label class="form-check form-check-inline">
                    <input class="form-check-input wage_type" type="radio" value="1" id="white_collar" name="wage_type"
                        <?php echo $white_checked ?? ''; ?>>
                    <span class="form-check-label">Beyaz Yaka</span>
                </label>
            </div>
        </div>

    </div>
    <div class="row mt-2">
        <div class="col-md-2">
            <label for="">Telefon</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" placeholder="505 555 55 55" name="phone"
                value="<?php echo $person->phone ?? ''; ?>">
        </div>
        <div class="col-md-2 mt-2">
            <label for="">Email Adresi</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="email" value="<?php echo $person->email ?? ''; ?>">
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-2">
            <label for="">Çalıştığ Firma</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="company_id" value="<?php echo $person->company_id ?? ''; ?>">
        </div>
        <div class="col-md-2 mt-2">
            <label for="">İban Numarası</label>
        </div>
        <div class="col-md-4">

            <input type="text" class="form-control" name="iban_number"
                value="<?php echo $person->iban_number ?? ''; ?>">
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-2">
            <label for="">Grubu</label>
        </div>
        <div class="col-md-4">
            <?php echo $jobGroups->jobGroupsSelect("job_groups", $person->job_group ?? ''); ?>
        </div>
        <div class="col-md-2 mt-2">
            <label for="">Görevi</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="job" value="<?php echo $person->job ?? ''; ?>">
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-2">
            <label for="">Adresi</label>
        </div>
        <div class="col-md-4">
            <textarea class="form-control" style="min-height:100px" name="address"
                id="address"><?php echo $person->address ?? ''; ?></textarea>
        </div>
        <div class="col-md-2 mt-2">
            <label for="">Açıklama</label>
        </div>
        <div class="col-md-4">
            <textarea class="form-control" style="min-height:100px" placeholder="Personel hakkında not ekleyebilirsiniz"
                name="aciklama" id="aciklama"><?php echo $person->aciklama ?? ''; ?></textarea>
        </div>

    </div>
</form>