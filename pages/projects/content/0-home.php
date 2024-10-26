<div class="row mt-3">
    <div class="col-md-2">
        <label for="">Proje Adı</label>
    </div>
    <div class="col-md-4">
        <input type="text" class="form-control" name="project_name" value="<?php echo $project->project_name ?? '' ?>">
    </div>

    <div class="col-md-2">
        <label for="">Başlangıç Tarihi</label>
    </div>
    <div class="col-md-4">
        <input type="text" class="form-control flatpickr" name="start_date"
            value="<?php echo $project->start_date ?? '' ?>">
    </div>
</div>

<div class="row mt-3">

    <div class="col-md-2">
        <label for="">Sözleşmesi</label>
    </div>
    <div class="col-md-4">
        <input type="file" class="form-control" name="project_file" value="<?php echo $project->project_file ?? '' ?>">
    </div>
    <?php
    if ($id == 0) {
        ?>
        <div class="col-md-2">
            <label for="">Açılış Bütçe</label>
        </div>
        <div class="col-md-4">
            <input type="text" class="form-control" name="budget" value="<?php echo $project->budget ?? '' ?>">
        </div>
    <?php } ?>
</div>
<div class="row mt-3">

    <div class="col-md-2">
        <label for="">Not</label>
    </div>
    <div class="col-md-10">
        <textarea class="form-control" name="project"
            placeholder="Proje hakkında not ekleyebilirsiniz"><?php echo $project->notes ?? '' ?></textarea>
    </div>
</div>