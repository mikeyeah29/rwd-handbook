<?php

declare(strict_types=1);

const HANDBOOK_ROOT = __DIR__;
const HANDBOOK_TITLE = 'Rockett Web Design handbook';
const EXCLUDED_DOCUMENTS = ['AGENTS.md', 'PRODUCT.md'];

$autoload = HANDBOOK_ROOT . '/vendor/autoload.php';
if (is_file($autoload)) {
    require_once $autoload;
}

function slug_title(string $value): string
{
    $value = preg_replace('/^\d+[-_]?/', '', $value) ?? $value;
    $value = preg_replace('/[-_]+/', ' ', $value) ?? $value;
    return ucwords(trim($value));
}

function page_title_from_markdown(string $markdown, string $fallback): string
{
    if (preg_match('/^#\s+(.+)$/m', $markdown, $matches)) {
        return trim(strip_tags($matches[1]));
    }

    return slug_title($fallback);
}

function relative_document_path(string $path): string
{
    return ltrim(str_replace(HANDBOOK_ROOT, '', $path), DIRECTORY_SEPARATOR);
}

function all_markdown_files(): array
{
    $files = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator(HANDBOOK_ROOT, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if (!$file->isFile() || strtolower($file->getExtension()) !== 'md') {
            continue;
        }

        $relative = relative_document_path($file->getPathname());
        if (str_starts_with($relative, '.git' . DIRECTORY_SEPARATOR) || str_starts_with($relative, 'vendor' . DIRECTORY_SEPARATOR)) {
            continue;
        }

        if (in_array($relative, EXCLUDED_DOCUMENTS, true)) {
            continue;
        }

        $files[] = $relative;
    }

    sort($files, SORT_NATURAL | SORT_FLAG_CASE);
    return $files;
}

function folder_name(string $folder): string
{
    return slug_title($folder);
}

function folder_key(string $path): string
{
    return explode(DIRECTORY_SEPARATOR, $path)[0] ?? '';
}

function page_url(string $path): string
{
    return 'index.php?page=' . rawurlencode($path);
}

function safe_document_path(string $requested): ?string
{
    $requested = ltrim(str_replace(['\\', "\0"], ['/', ''], $requested), '/');
    if ($requested === '' || !str_ends_with(strtolower($requested), '.md')) {
        return null;
    }

    $candidate = realpath(HANDBOOK_ROOT . DIRECTORY_SEPARATOR . $requested);
    $root = realpath(HANDBOOK_ROOT);

    if ($candidate === false || $root === false || !str_starts_with($candidate, $root . DIRECTORY_SEPARATOR) || !is_file($candidate)) {
        return null;
    }

    return $candidate;
}

function render_markdown(string $markdown): string
{
    if (class_exists(Parsedown::class)) {
        $parsedown = new Parsedown();
        $parsedown->setSafeMode(true);
        return $parsedown->text($markdown);
    }

    // A small fallback keeps the viewer usable before `composer install` has run.
    $escaped = htmlspecialchars($markdown, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $escaped = preg_replace_callback('/```([a-zA-Z0-9_-]*)\n([\s\S]*?)```/', static function (array $match): string {
        $language = $match[1] !== '' ? ' class="language-' . htmlspecialchars($match[1], ENT_QUOTES, 'UTF-8') . '"' : '';
        return '<pre><code' . $language . '>' . rtrim($match[2], "\n") . '</code></pre>';
    }, $escaped) ?? $escaped;

    $lines = preg_split('/\r\n|\r|\n/', $escaped) ?: [];
    $html = [];
    $in_list = false;
    $list_type = '';
    $paragraph = [];

    $flush_paragraph = static function () use (&$html, &$paragraph): void {
        if ($paragraph !== []) {
            $html[] = '<p>' . implode(' ', $paragraph) . '</p>';
            $paragraph = [];
        }
    };

    $close_list = static function () use (&$html, &$in_list, &$list_type): void {
        if ($in_list) {
            $html[] = '</' . $list_type . '>';
            $in_list = false;
            $list_type = '';
        }
    };

    foreach ($lines as $line) {
        if (str_starts_with($line, '<pre><code') || str_starts_with($line, '</code></pre>')) {
            $flush_paragraph();
            $close_list();
            $html[] = $line;
            continue;
        }

        if (trim($line) === '') {
            $flush_paragraph();
            $close_list();
            continue;
        }

        if (preg_match('/^(#{1,6})\s+(.+)$/', $line, $match)) {
            $flush_paragraph();
            $close_list();
            $level = strlen($match[1]);
            $html[] = '<h' . $level . '>' . $match[2] . '</h' . $level . '>';
            continue;
        }

        if (preg_match('/^[-*]\s+(.+)$/', $line, $match) || preg_match('/^\d+\.\s+(.+)$/', $line, $match)) {
            $type = preg_match('/^\d+\./', $line) ? 'ol' : 'ul';
            $flush_paragraph();
            if (!$in_list || $list_type !== $type) {
                $close_list();
                $html[] = '<' . $type . '>';
                $in_list = true;
                $list_type = $type;
            }
            $html[] = '<li>' . $match[1] . '</li>';
            continue;
        }

        if (preg_match('/^>\s?(.+)$/', $line, $match)) {
            $flush_paragraph();
            $close_list();
            $html[] = '<blockquote><p>' . $match[1] . '</p></blockquote>';
            continue;
        }

        $paragraph[] = $line;
    }

    $flush_paragraph();
    $close_list();
    return implode("\n", $html);
}

$documents = all_markdown_files();
$folders = [];
$root_documents = [];

foreach ($documents as $document) {
    if (str_contains($document, DIRECTORY_SEPARATOR)) {
        $folder = folder_key($document);
        $folders[$folder][] = $document;
    } else {
        $root_documents[] = $document;
    }
}

uksort($folders, 'strnatcasecmp');
$requested_page = isset($_GET['page']) && is_string($_GET['page']) ? $_GET['page'] : '';
$document_path = safe_document_path($requested_page);
$is_document = $document_path !== null;
$current_relative = $is_document ? relative_document_path($document_path) : '';
$current_index = $is_document ? array_search($current_relative, $documents, true) : false;
$previous = $current_index !== false && $current_index > 0 ? $documents[$current_index - 1] : null;
$next = $current_index !== false && $current_index < count($documents) - 1 ? $documents[$current_index + 1] : null;
$page_markdown = $is_document ? (string) file_get_contents($document_path) : '';
$page_title = $is_document ? page_title_from_markdown($page_markdown, pathinfo($current_relative, PATHINFO_FILENAME)) : HANDBOOK_TITLE;
$active_folder = $is_document ? folder_key($current_relative) : '';
$active_folder_label = $active_folder !== '' ? folder_name($active_folder) : 'Root documents';
$total_documents = count($documents);
$folder_count = count($folders);

function document_link_label(string $path): string
{
    return page_title_from_markdown((string) @file_get_contents(HANDBOOK_ROOT . DIRECTORY_SEPARATOR . $path), pathinfo($path, PATHINFO_FILENAME));
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A readable browser view of the Rockett Web Design business handbook.">
    <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?> · RWD handbook</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600;700&family=Newsreader:opsz,wght@6..72,600;6..72,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<a class="skip-link" href="#content">Skip to content</a>
<div class="site-shell">
    <header class="site-header">
        <a class="brand" href="index.php" aria-label="RWD handbook home">
            <span class="brand-mark" aria-hidden="true">R</span>
            <span>
                <span class="brand-name">Rockett Web Design</span>
                <span class="brand-subtitle">working handbook</span>
            </span>
        </a>
        <div class="header-note"><span class="status-dot"></span><?= $total_documents ?> documents · <?= $folder_count ?> sections</div>
    </header>

    <?php if (!$is_document): ?>
        <main id="content" class="home-content">
            <section class="intro-block">
                <p class="eyebrow">The reason behind the work</p>
                <h1>Build toward<br><em>£6k–£10k months.</em></h1>
                <p class="intro-copy">This handbook keeps the real goal in view: build Rockett Web Design into a business that generates £6,000–£10,000 per month and moves me away from hourly-paid work. Every note should help make that possible.</p>
            </section>

            <section class="index-panel" aria-labelledby="index-heading">
                <div class="section-heading-row">
                    <div>
                        <p class="eyebrow">Browse the handbook</p>
                        <h2 id="index-heading">Choose a section</h2>
                    </div>
                    <label class="search-field">
                        <span class="sr-only">Filter sections and documents</span>
                        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5"></circle><path d="m16 16 4.5 4.5"></path></svg>
                        <input id="handbook-filter" type="search" placeholder="Filter the handbook" autocomplete="off">
                    </label>
                </div>

                <div class="folder-list">
                    <?php foreach ($folders as $folder => $folder_documents): ?>
                        <section class="folder-row" data-filter-item data-filter-text="<?= htmlspecialchars(folder_name($folder) . ' ' . implode(' ', array_map('document_link_label', $folder_documents)), ENT_QUOTES, 'UTF-8') ?>">
                            <div class="folder-index"><?= htmlspecialchars(substr($folder, 0, 2), ENT_QUOTES, 'UTF-8') ?></div>
                            <div class="folder-main">
                                <div class="folder-topline">
                                    <h3><?= htmlspecialchars(folder_name($folder), ENT_QUOTES, 'UTF-8') ?></h3>
                                    <span class="folder-count"><?= count($folder_documents) ?> <?= count($folder_documents) === 1 ? 'document' : 'documents' ?></span>
                                </div>
                                <div class="document-links">
                                    <?php foreach ($folder_documents as $document): ?>
                                        <a href="<?= htmlspecialchars(page_url($document), ENT_QUOTES, 'UTF-8') ?>">
                                            <span><?= htmlspecialchars(document_link_label($document), ENT_QUOTES, 'UTF-8') ?></span>
                                            <span class="link-arrow" aria-hidden="true">↗</span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </section>
                    <?php endforeach; ?>

                    <?php if ($root_documents !== []): ?>
                        <section class="folder-row root-documents" data-filter-item data-filter-text="<?= htmlspecialchars(implode(' ', array_map('document_link_label', $root_documents)), ENT_QUOTES, 'UTF-8') ?>">
                            <div class="folder-index">—</div>
                            <div class="folder-main">
                                <div class="folder-topline"><h3>Loose documents</h3><span class="folder-count"><?= count($root_documents) ?> <?= count($root_documents) === 1 ? 'document' : 'documents' ?></span></div>
                                <div class="document-links">
                                    <?php foreach ($root_documents as $document): ?>
                                        <a href="<?= htmlspecialchars(page_url($document), ENT_QUOTES, 'UTF-8') ?>"><span><?= htmlspecialchars(document_link_label($document), ENT_QUOTES, 'UTF-8') ?></span><span class="link-arrow" aria-hidden="true">↗</span></a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </section>
                    <?php endif; ?>
                </div>
                <p id="filter-empty" class="filter-empty" hidden>No sections match that search.</p>
            </section>
        </main>
    <?php else: ?>
        <div class="reading-layout">
            <aside class="sidebar" aria-label="Handbook navigation">
                <a class="back-link" href="index.php"><span aria-hidden="true">←</span> All sections</a>
                <nav class="sidebar-nav">
                    <?php foreach ($folders as $folder => $folder_documents): ?>
                        <div class="sidebar-group <?= $active_folder === $folder ? 'is-active' : '' ?>">
                            <div class="sidebar-group-title"><span><?= htmlspecialchars(substr($folder, 0, 2), ENT_QUOTES, 'UTF-8') ?></span><?= htmlspecialchars(folder_name($folder), ENT_QUOTES, 'UTF-8') ?></div>
                            <?php if ($active_folder === $folder): ?>
                                <div class="sidebar-pages">
                                    <?php foreach ($folder_documents as $document): ?>
                                        <a class="sidebar-page <?= $document === $current_relative ? 'is-current' : '' ?>" href="<?= htmlspecialchars(page_url($document), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars(document_link_label($document), ENT_QUOTES, 'UTF-8') ?></a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </nav>
            </aside>

            <main id="content" class="document-content">
                <div class="document-meta">
                    <span><?= htmlspecialchars($active_folder_label, ENT_QUOTES, 'UTF-8') ?></span>
                    <span class="meta-separator">/</span>
                    <span><?= str_pad((string) (($current_index !== false ? $current_index : 0) + 1), 2, '0', STR_PAD_LEFT) ?> of <?= str_pad((string) $total_documents, 2, '0', STR_PAD_LEFT) ?></span>
                </div>
                <article class="markdown-body">
                    <?= render_markdown($page_markdown) ?>
                </article>
                <nav class="document-pagination" aria-label="Document pagination">
                    <?php if ($previous): ?><a class="pagination-link pagination-previous" href="<?= htmlspecialchars(page_url($previous), ENT_QUOTES, 'UTF-8') ?>"><span class="pagination-label">Previous</span><span><?= htmlspecialchars(document_link_label($previous), ENT_QUOTES, 'UTF-8') ?></span></a><?php else: ?><span></span><?php endif; ?>
                    <?php if ($next): ?><a class="pagination-link pagination-next" href="<?= htmlspecialchars(page_url($next), ENT_QUOTES, 'UTF-8') ?>"><span class="pagination-label">Next</span><span><?= htmlspecialchars(document_link_label($next), ENT_QUOTES, 'UTF-8') ?></span></a><?php endif; ?>
                </nav>
            </main>
        </div>
    <?php endif; ?>

    <footer class="site-footer"><span>RWD / private working notes</span><span>Rendered from Markdown</span></footer>
</div>
<script src="assets/app.js"></script>
</body>
</html>
