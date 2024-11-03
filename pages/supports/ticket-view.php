<?php
require_once 'Model/SupportsMessagesModel.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$SupportsMessages = new SupportsMessagesModel();
$messages = $SupportsMessages->getMessagesByTicketId($id);

?>


<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">

<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row mb-3">

                <!-- <div class="col-auto ms-auto d-print-none me-2">
                    <button type="button" class="btn btn-outline-secondary route-link" data-page="persons/list">
                        <i class="ti ti-list icon me-2"></i>
                        Listeye Dön
                    </button>
                </div> -->
                <div class="col-auto ms-auto d-print-none me-2">
                    <button type="button" class="btn btn-primary route-link" data-page="persons/list">
                        <i class="ti ti-list icon me-2"></i>
                        Bildirimi Kapat
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <ul class="cbp_tmtimeline">
                        <?php
                        foreach ($messages as $message) {
                            ?>
                            <li>
                                <time class="cbp_tmtime" datetime="2017-11-04T03:45"><span>03:45 AM</span>
                                    <span>Today</span></time>
                                <div class="cbp_tmicon bg-info"><i class="zmdi zmdi-label"></i></div>
                                <div class="cbp_tmlabel">
                                    <h2><a href="javascript:void(0);">Art Ramadani</a> <span>posted a status update</span>
                                    </h2>
                                    <p><?php echo strip_tags($message->message); ?></p>
                                </div>
                            </li>
                        <?php } ?>

                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>