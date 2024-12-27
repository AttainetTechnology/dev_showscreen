<?php if ($pager->hasPreviousPage()): ?>
    <a href="<?= $pager->getPreviousPage() ?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
    </a>
<?php endif ?>

<?php foreach ($pager->links() as $link): ?>
    <a href="<?= $link['uri'] ?>" <?= $link['active'] ? 'class="active"' : '' ?>>
        <?= $link['title'] ?>
    </a>
<?php endforeach ?>

<?php if ($pager->hasNextPage()): ?>
    <a href="<?= $pager->getNextPage() ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
    </a>
<?php endif ?>