<?php

class Table
{
    private array $columns;   // ['key' => 'Заголовок', ...]
    private array $rows;      // [['key' => 'value', ...], ...]
    private string $id;
    private bool $searchable;
    private bool $sortable;
    private array $filters;   // ['key' => ['label' => '...', 'options' => ['a','b']]]
    private string $emptyText;

    public function __construct(
        array $columns,
        array $rows = [],
        string $id = 'datatable',
        bool $searchable = true,
        bool $sortable = true,
        array $filters = [],
        string $emptyText = 'Ничего не найдено'
    ) {
        $this->columns    = $columns;
        $this->rows       = $rows;
        $this->id         = $id;
        $this->searchable = $searchable;
        $this->sortable   = $sortable;
        $this->filters    = $filters;
        $this->emptyText  = $emptyText;
    }

    public static function make(...$args): self
    {
        return new self(...$args);
    }

    public function render(): string
    {
        $html  = '<div class="dt-wrap" id="' . htmlspecialchars($this->id) . '-wrap">';
        $html .= $this->renderToolbar();
        $html .= $this->renderTable();
        $html .= '</div>';
        return $html;
    }

    public function __toString(): string
    {
        return $this->render();
    }

    private function renderToolbar(): string
    {
        $html = '<div class="dt-toolbar">';

        if ($this->searchable) {
            $html .= '
            <div class="dt-search">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                </svg>
                <input type="search" class="dt-search-input" placeholder="Поиск..." data-dt="' . htmlspecialchars($this->id) . '">
            </div>';
        }

        if (!empty($this->filters)) {
            $html .= '<div class="dt-filters">';
            foreach ($this->filters as $key => $filter) {
                $label = htmlspecialchars($filter['label'] ?? $key);
                $html .= '<select class="dt-filter" data-col="' . htmlspecialchars($key) . '" data-dt="' . htmlspecialchars($this->id) . '">';
                $html .= '<option value="">' . $label . ': все</option>';
                foreach ($filter['options'] as $opt) {
                    $v = htmlspecialchars((string)$opt);
                    $html .= '<option value="' . $v . '">' . $v . '</option>';
                }
                $html .= '</select>';
            }
            $html .= '</div>';
        }

        $html .= '<div class="dt-count"><span class="dt-visible">0</span> / <span class="dt-total">0</span></div>';
        $html .= '</div>';
        return $html;
    }

    private function renderTable(): string
    {
        $html = '<div class="dt-table-scroll">';
        $html .= '<table class="dt-table" id="' . htmlspecialchars($this->id) . '">';
        $html .= '<thead><tr>';

        $i = 0;
        foreach ($this->columns as $key => $label) {
            $sortAttr = $this->sortable
                ? ' class="dt-sortable" data-col="' . $i . '" data-key="' . htmlspecialchars($key) . '"'
                : '';
            $html .= '<th' . $sortAttr . '>';
            $html .= '<span>' . htmlspecialchars($label) . '</span>';
            if ($this->sortable) {
                $html .= '<span class="dt-sort-icon" aria-hidden="true"></span>';
            }
            $html .= '</th>';
            $i++;
        }
        $html .= '</tr></thead><tbody>';

        if (empty($this->rows)) {
            $colspan = count($this->columns);
            $html .= '<tr class="dt-empty"><td colspan="' . $colspan . '">' . htmlspecialchars($this->emptyText) . '</td></tr>';
        } else {
            foreach ($this->rows as $row) {
                $html .= '<tr>';
                foreach ($this->columns as $key => $label) {
                    $val = $row[$key] ?? '';
                    // data-value для сортировки/фильтра (сырое значение)
                    $raw = is_scalar($val) ? (string)$val : '';
                    $html .= '<td data-value="' . htmlspecialchars($raw) . '">';
                    // Можно передать HTML в значении (если нужно)
                    $html .= is_string($val) && str_contains($val, '<')
                        ? $val
                        : htmlspecialchars($raw);
                    $html .= '</td>';
                }
                $html .= '</tr>';
            }
        }

        $html .= '</tbody></table></div>';
        return $html;
    }
}