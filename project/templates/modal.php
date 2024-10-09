<div class="toast-container alert-modal" id="alert-modal">
    <?php if (!is_null($modalList)) : ?>
        <?php foreach ($modalList as $modalItem) : ?>

            <!-- ТОСТ -->
            <?php $id = $modalItem['id'] ?? ''; ?>
            <?php $category = $modalItem['category'] ?? ''; ?>
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-set-category="<?= $category; ?>" data-set-popup-id="<?= $id; ?>">
                <div class="toast-header">
                    <?= $modalItem['title'] ?? ''; ?>

                    <?php if (in_array($modalItem['category'] ?? null, ['error', 'link', 'message'])) : ?>
                        <button type="button" class="btn-close me-0 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    <?php endif; ?>
                </div>

                <div class="toast-body">
                    <div class="mt-0 pt-0">
                        <?php $categoryName = $modalItem['category']; ?>

                        <?php
                        switch ($categoryName) {
                            case 'city':
                                if (!empty($modalItem['button'])) {
                                    foreach ($modalItem['button'] as $modalButtonItem) {
                                        echo "<button type='button' class='{$modalButtonItem['class']} me-2'>{$modalButtonItem['text']}</button>";
                                    }
                                }
                                break;

                            case 'error':
                                echo $modalItem['text'];
                                break;

                            case 'message':
                                echo $modalItem['text'];
                                break;

                            case 'link':
                                $linkInfo = $modalItem['link'];
                                echo "<a href='{$linkInfo['address']}'>{$linkInfo['linkTitle']}</a>";
                                break;
                        }
                        ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>