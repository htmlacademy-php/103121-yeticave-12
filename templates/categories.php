<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $category): ?>
        <li class="nav__item">
            <a href="category.php?category=<?= $category['id'] ?>"><?= htmlspecialchars($category['name'], ENT_QUOTES); ?></a>
        </li>
        <?php endforeach; ?>
    </ul>
</nav>
