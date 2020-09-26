<?php if ($pages_count > 1): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
            <a href=<?= $current_page > 1
            ? "/$type.php?page="
            . ($current_page - 1)
            . "&$type=" . htmlspecialchars($search, ENT_QUOTES)
            : '#'; ?> disabled>Назад</a>
        </li>
        <?php foreach ($pages as $page): ?>
            <li class="pagination-item <?= ((int)$page === $current_page) ? 'pagination-item-active' : '' ?>">
                <a href="/<?=$type;?>.php?page=<?=$page;?>&<?=$type;?>=<?= htmlspecialchars($search, ENT_QUOTES); ?>"><?=$page;?></a>
            </li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next">
            <a href=<?= $current_page < count($pages)
            ? "/$type.php?page="
            . ($current_page + 1)
            . "&$type=" . htmlspecialchars($search, ENT_QUOTES)
            : '#'; ?>>Вперед</a>
        </li>
    </ul>
<?php endif; ?>
