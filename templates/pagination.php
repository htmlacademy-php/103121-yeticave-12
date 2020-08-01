<?php if ($pages_count > 1): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev">
            <a href=<?= $current_page > 1
            ? '/search.php?page='
            . ($current_page - 1)
            . '&search=' . htmlspecialchars($search, ENT_QUOTES)
            : '#'; ?> disabled>Назад</a>
        </li>
        <?php foreach ($pages as $page): ?>
            <li class="pagination-item <?= ((int)$page === $current_page) ? 'pagination-item-active' : '' ?>">
                <a href="/search.php?page=<?=$page;?>&search=<?= htmlspecialchars($search, ENT_QUOTES); ?>"><?=$page;?></a>
            </li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next">
            <a href=<?= $current_page < count($pages)
            ? '/search.php?page='
            . ($current_page + 1)
            . '&search=' . htmlspecialchars($search, ENT_QUOTES)
            : '#'; ?>>Вперед</a>
        </li>
    </ul>
<?php endif; ?>
