<?php

require_once 'Model/Auths.php';

$authObj = new Auths();
$auths = $authObj->auths();
?>

<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Yetkileri Düzenle
                    </h2>
                </div>

                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <button type="button" class="btn btn-outline-secondary route-link" data-page="users/list">
                        <i class="ti ti-list icon me-2"></i>
                        Listeye Dön
                    </button>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <button type="button" class="btn btn-primary" id="kullanici_kaydet">
                        <i class="ti ti-device-floppy icon me-2"></i>
                        Kaydet
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .perm-border {
            /* display: flex;
            flex-wrap: wrap; */
            gap: 5px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            margin: 5px;

        }
    </style>
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-body">
                    <div class="row g-2 mt-3">
                        <?php
                        foreach ($auths as $auth) {
                        ?>
                            <div class="col-auto">
                                <label class="form-colorinput fw-bold">
                                    <?php echo $auth->title ?>
                                </label>
                            </div>
                            <div class="perm-border">
                                <div class="row">
                                    <div class="col-auto">
                                        <label class="form-colorinput">
                                            <input name="color<?php echo $auth->id; ?>" type="radio" value="red" class="form-colorinput-input">
                                            <span class="form-colorinput-color bg-red"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto">
                                        <label class="form-colorinput">
                                            <input name="color<?php echo $auth->id; ?>" type="radio" value="orange" class="form-colorinput-input">
                                            <span class="form-colorinput-color bg-orange" style="color:orange"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto">
                                        <label class="form-colorinput">
                                            <input name="color<?php echo $auth->id; ?>" type="radio" value="yellow" class="form-colorinput-input">
                                            <span class="form-colorinput-color bg-yellow"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto">
                                        <label class="form-colorinput">
                                            <input name="color<?php echo $auth->id; ?>" type="radio" value="lime" class="form-colorinput-input">
                                            <span class="form-colorinput-color bg-lime"></span>
                                        </label>
                                    </div>

                                </div>


                                <?php
                                $sub_auths = $authObj->subAuths($auth->id);
                                foreach ($sub_auths as $sub_auth) {

                                ?>
                                    <div class="row ps-3 pe-4 pt-2">
                                        <div class="perm-border">
                                        <div class="col-auto">
                                            <label class="form-colorinput">
                                                <?php echo $sub_auth->title ?>
                                            </label>
                                        </div>
                                            <div class="row">


                                                <div class="col-auto">
                                                    <label class="form-colorinput">
                                                        <input name="color<?php echo $sub_auth->id; ?>" 
                                                        data-desc="<?php echo $sub_auth->tooltip_1; ?>"
                                                        type="radio" value="red" class="form-colorinput-input">
                                                        <span class="form-colorinput-color bg-red"></span>
                                                    </label>
                                                </div>
                                                <div class="col-auto">
                                                    <label class="form-colorinput">
                                                        <input name="color<?php echo $sub_auth->id; ?>" 
                                                         data-desc="<?php echo $sub_auth->tooltip_2; ?>"
                                                        type="radio" value="orange" class="form-colorinput-input">
                                                        <span class="form-colorinput-color bg-orange" style="color:orange"></span>
                                                    </label>
                                                </div>
                                                <div class="col-auto">
                                                    <label class="form-colorinput">
                                                        <input name="color<?php echo $sub_auth->id; ?>" 
                                                         data-desc="<?php echo $sub_auth->tooltip_3; ?>"
                                                        type="radio" value="yellow" class="form-colorinput-input">
                                                        <span class="form-colorinput-color bg-yellow"></span>
                                                    </label>
                                                </div>
                                                <div class="col-auto">
                                                    <label class="form-colorinput">
                                                        <input name="color<?php echo $sub_auth->id; ?>" 
                                                         data-desc="<?php echo $sub_auth->tooltip_4; ?>"
                                                        type="radio" value="lime" class="form-colorinput-input">
                                                        <span class="form-colorinput-color bg-lime"></span>
                                                    </label>
                                                </div>
                                                                                            
                                                <div class="col-auto">
                                                    <label class="input-tooltip">
                                                   
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                <?php } ?>

                            </div>

                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    $('.form-colorinput-input').on('click', function() {
        let tooltip_data = $(this).attr('data-desc');

        console.log(tooltip_data);
        
        // .input-tooltip öğesine ulaşın
        var tooltip = $(this).closest('.col-auto').siblings().find('.input-tooltip');
        
        // .input-tooltip üzerinde işlem yapın (örneğin, metni değiştirin)
        tooltip.text(tooltip_data);
    });
});
</script>