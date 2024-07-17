<?php include(__DIR__.'/../backend/appointment.php'); ?>

<div class="container" id="i-card">
    <div class="row">
        <?php foreach ($turkeyData as $item) {?>
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card text-bg-primary h-100">
                    <div class="card-header"><?= $item['mission_country']?> Randevusu</div>
                    <div class="card-body">
                        <p class="card-text">Randevu Tarihi: <?= $item['appointment_date']?></p>
                        <p class="card-text">Başvuru Kategorisi: <?= $item['visa_category']?></p>
                        <p class="card-text">Vize Türü: <?= $item['visa_subcategory']?></p>
                        <p class="card-text">Başvuru Merkezi: <?= $item['center_name']?></p>
                        <a href="<?= $item['book_now_link']?>" target="_blank" class="btn btn-warning">Randevu Al</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
