<?php if(!empty($data)): ?>
    <?php foreach ($data as $key => $value): ?>
        <ul class="recent-searches">
            <li><a>Kỳ thi: <?= $value->exam_display ?></a></li>
            <li><a><?= $value->full_name_display ?></a></li>
            <li><a><?= strtoupper($value->level) ?></a></li>
            <li><a><?= $value->birthday_display ?> </a></li>
            <li><a><?= $value->phone ?></a></li>
            <li><a><?= !empty($value->is_payment) ? "Đã chuyển khoản" : "Chưa chuyển khoản" ?></a></li>
        </ul>
    <?php endforeach; ?>
<?php endif; ?>
