<style>
    .breadcrumb-container {
        display: flex !important;
        justify-content: flex-start !important;
        padding: 10px;
        margin-top: 30px;
        margin-bottom: -15px;
        background-color: #f7f8fa;
        border-radius: 5px;
        font-family: Arial, sans-serif;
    }

    .breadcrumb {
        display: flex !important;
        align-items: center !important;
        list-style: none !important;
        padding: 0px !important;
        margin: 0px !important;
        background-color: #f7f8fa !important;
    }

    .breadcrumb-item {
        font-size: 14px;
        font-weight: 500;
    }

    .breadcrumb-item a {
        color: #125691 !important;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .breadcrumb-item a:hover {
        color: #0056b3;
    }

    .breadcrumb-item.active {
        color: #7aa3c7 !important;
        background-color: #f7f8fa !important;
        cursor: default;
    }

    .breadcrumb-separator {
        margin: 0 8px;
        color: #6c757d;
        font-size: 14px;
    }
</style>
<?php if (isset($amiga) && !empty($amiga)) : ?>
    <nav class="breadcrumb-container" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <?php foreach ($amiga as $index => $breadcrumb): ?>
                <li class="breadcrumb-item<?= $index === array_key_last($amiga) ? ' active' : '' ?>">
                    <?php if ($breadcrumb['link'] !== '#' && $index !== array_key_last($amiga)): ?>
                        <a href="<?= esc($breadcrumb['link']) ?>"><?= esc($breadcrumb['title']) ?></a>
                    <?php else: ?>
                        <?= esc($breadcrumb['title']) ?>
                    <?php endif; ?>
                </li>
                <?php if ($index !== array_key_last($amiga)) : ?>
                    <span class="breadcrumb-separator"> &gt; </span>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>
<?php endif; ?>