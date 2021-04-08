<aside class="menu">
    <ul class="menu-list">
        <?php foreach($_Menu as $menu) : ?>
            <li>
                <a class="<?= ($_ActiveSlug === $menu['slug']) ? 'is-active' : '' ?>" href="<?= $menu['url'] ?>">
                    <span class="icon">
                        <i class="<?= $menu['icon'] ?>"></i>
                    </span>
                    <?= $menu['title'] ?>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
</aside>