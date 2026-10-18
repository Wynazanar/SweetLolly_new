<?php

class Table
{
    private array $columns;
    private array $rows;
    private string $id;
    private bool $searchable;
    private bool $sortable;
    private array $filters;
    private string $emptyText;

    // server-side
    private ?string $apiUrl;      // например http://localhost:3000/bw-top
    private string $dataKey;      // ключ в JSON: "bw-top"
    private int $page;
    private int $pageSize;
    private int $total;
    private int $pages;
    private string $orderBy;
    private string $order;

    public function __construct(
        array $columns,
        array $rows = [],
        string $id = 'datatable',
        bool $searchable = true,
        bool $sortable = true,
        array $filters = [],
        string $emptyText = 'Ничего не найдено',
        ?string $apiUrl = null,
        string $dataKey = '',
        int $page = 1,
        int $pageSize = 20,
        int $total = 0,
        int $pages = 1,
        string $orderBy = '',
        string $order = 'desc'
    ) {
        $this->columns    = $columns;
        $this->rows       = $rows;
        $this->id         = $id;
        $this->searchable = $searchable;
        $this->sortable   = $sortable;
        $this->filters    = $filters;
        $this->emptyText  = $emptyText;
        $this->apiUrl     = $apiUrl;
        $this->dataKey    = $dataKey !== '' ? $dataKey : $id;
        $this->page       = max(1, $page);
        $this->pageSize   = max(1, $pageSize);
        $this->total      = $total;
        $this->pages      = max(1, $pages);
        $this->orderBy    = $orderBy;
        $this->order      = strtolower($order) === 'asc' ? 'asc' : 'desc';
    }

    public static function make(...$args): self
    {
        return new self(...$args);
    }

    public function render(): string
    {
        $html  = '<div class="dt-wrap" id="' . htmlspecialchars($this->id) . '-wrap"';
        $html .= ' data-api="' . htmlspecialchars($this->apiUrl ?? '') . '"';
        $html .= ' data-key="' . htmlspecialchars($this->dataKey) . '"';
        $html .= ' data-page="' . $this->page . '"';
        $html .= ' data-pages="' . $this->pages . '"';
        $html .= ' data-total="' . $this->total . '"';
        $html .= ' data-limit="' . $this->pageSize . '"';
        $html .= ' data-order-by="' . htmlspecialchars($this->orderBy) . '"';
        $html .= ' data-order="' . htmlspecialchars($this->order) . '"';
        $html .= '>';

        $html .= $this->renderToolbar();
        $html .= $this->renderTable();
        $html .= '<div class="dt-pagination" id="' . htmlspecialchars($this->id) . '-pager"></div>';
        $html .= '</div>';

        // JS только если есть API (server-side)
        if ($this->apiUrl) {
            $html .= $this->renderScript();
        }

        return $html;
    }

    public function __toString(): string
    {
        return $this->render();
    }

    // -------------------------------------------------------------------------

    private function renderToolbar(): string
    {
        $html = '<div class="dt-toolbar">';

        if ($this->searchable) {
            $html .= '
            <div class="dt-search">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
                </svg>
                <input type="search" class="dt-search-input" placeholder="Поиск по нику..." id="' . htmlspecialchars($this->id) . '-search">
            </div>';
        }

        $html .= '<div class="dt-count">
            <span class="dt-visible" id="' . htmlspecialchars($this->id) . '-visible">' . count($this->rows) . '</span>
            /
            <span class="dt-total" id="' . htmlspecialchars($this->id) . '-total">' . $this->total . '</span>
        </div>';

        $html .= '</div>';
        return $html;
    }

    private function renderTable(): string
    {
        $html = '<div class="dt-table-scroll"><table class="dt-table" id="' . htmlspecialchars($this->id) . '">';
        $html .= '<thead><tr>';

        foreach ($this->columns as $key => $label) {
            $cls = $this->sortable ? ' class="dt-sortable"' : '';
            $sortMark = '';
            if ($this->sortable && $key === $this->orderBy) {
                $cls = ' class="dt-sortable ' . ($this->order === 'asc' ? 'asc' : 'desc') . '"';
            }
            $html .= '<th' . $cls . ' data-key="' . htmlspecialchars($key) . '">';
            $html .= '<span>' . htmlspecialchars($label) . '</span>';
            if ($this->sortable) {
                $html .= '<span class="dt-sort-icon" aria-hidden="true"></span>';
            }
            $html .= '</th>';
        }

        $html .= '</tr></thead><tbody id="' . htmlspecialchars($this->id) . '-body">';
        $html .= $this->renderRows($this->rows);
        $html .= '</tbody></table></div>';
        return $html;
    }

    private function renderRows(array $rows): string
    {
        if (empty($rows)) {
            $colspan = count($this->columns);
            return '<tr class="dt-empty"><td colspan="' . $colspan . '">' . htmlspecialchars($this->emptyText) . '</td></tr>';
        }

        $html = '';
        foreach ($rows as $row) {
            $html .= '<tr>';
            foreach ($this->columns as $key => $label) {
                $val = $row[$key] ?? '';
                $raw = is_scalar($val) ? (string)$val : '';
                $html .= '<td data-value="' . htmlspecialchars($raw) . '">';
                $html .= is_string($val) && str_contains($val, '<')
                    ? $val
                    : htmlspecialchars($raw);
                $html .= '</td>';
            }
            $html .= '</tr>';
        }
        return $html;
    }

    // -------------------------------------------------------------------------
    // Встроенный JS (один раз на таблицу)
    // -------------------------------------------------------------------------

    private function renderScript(): string
    {
        $id = json_encode($this->id);
        $cols = json_encode(array_keys($this->columns));

        return <<<JS
<script>
(function(){
  const wrap = document.getElementById({$id} + '-wrap');
  if (!wrap || wrap.dataset.bound) return;
  wrap.dataset.bound = '1';

  const api     = wrap.dataset.api;
  const dataKey = wrap.dataset.key;
  const cols    = {$cols};
  const body    = document.getElementById({$id} + '-body');
  const pager   = document.getElementById({$id} + '-pager');
  const search  = document.getElementById({$id} + '-search');
  const elVis   = document.getElementById({$id} + '-visible');
  const elTot   = document.getElementById({$id} + '-total');

  let state = {
    page:    parseInt(wrap.dataset.page, 10) || 1,
    pages:   parseInt(wrap.dataset.pages, 10) || 1,
    total:   parseInt(wrap.dataset.total, 10) || 0,
    limit:   parseInt(wrap.dataset.limit, 10) || 20,
    orderBy: wrap.dataset.orderBy || '',
    order:   wrap.dataset.order || 'desc',
    search:  ''
  };

  let debounceTimer = null;
  let loading = false;

  function esc(s) {
    return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  function renderRows(rows) {
    if (!rows || !rows.length) {
      body.innerHTML = '<tr class="dt-empty"><td colspan="' + cols.length + '">Ничего не найдено</td></tr>';
      return;
    }
    body.innerHTML = rows.map(row => {
      return '<tr>' + cols.map(k => {
        const v = row[k] ?? '';
        return '<td data-value="' + esc(v) + '">' + esc(v) + '</td>';
      }).join('') + '</tr>';
    }).join('');
  }

  function renderPager() {
    if (!pager) return;
    const { page, pages } = state;
    if (pages <= 1) { pager.innerHTML = ''; return; }

    let html = '';
    html += '<button class="dt-page-btn" data-p="' + (page-1) + '"' + (page<=1?' disabled':'') + '>‹</button>';

    const max = 5;
    let from = Math.max(1, page - Math.floor(max/2));
    let to   = Math.min(pages, from + max - 1);
    if (to - from < max - 1) from = Math.max(1, to - max + 1);

    if (from > 1) {
      html += '<button class="dt-page-btn" data-p="1">1</button>';
      if (from > 2) html += '<span class="dt-page-dots">…</span>';
    }
    for (let i = from; i <= to; i++) {
      html += '<button class="dt-page-btn' + (i===page?' active':'') + '" data-p="' + i + '">' + i + '</button>';
    }
    if (to < pages) {
      if (to < pages-1) html += '<span class="dt-page-dots">…</span>';
      html += '<button class="dt-page-btn" data-p="' + pages + '">' + pages + '</button>';
    }
    html += '<button class="dt-page-btn" data-p="' + (page+1) + '"' + (page>=pages?' disabled':'') + '>›</button>';
    pager.innerHTML = html;
  }

  function setSortUI() {
    wrap.querySelectorAll('th.dt-sortable').forEach(th => {
      th.classList.remove('asc','desc');
      if (th.dataset.key === state.orderBy) {
        th.classList.add(state.order === 'asc' ? 'asc' : 'desc');
      }
    });
  }

  async function load(resetPage) {
    if (loading || !api) return;
    if (resetPage) state.page = 1;
    loading = true;
    wrap.classList.add('dt-loading');

    const q = new URLSearchParams({
      page:    state.page,
      limit:   state.limit,
      orderBy: state.orderBy,
      order:   state.order
    });
    if (state.search) q.set('search', state.search);

    try {
      const res  = await fetch(api + (api.includes('?') ? '&' : '?') + q.toString());
      const json = await res.json();
      const rows = json[dataKey] || [];
      const meta = json.meta || {};

      state.page  = meta.page  || state.page;
      state.pages = meta.pages || 1;
      state.total = meta.total || 0;

      renderRows(rows);
      renderPager();
      setSortUI();
      if (elVis) elVis.textContent = rows.length;
      if (elTot) elTot.textContent = state.total;
    } catch (e) {
      console.error('Table load error', e);
      body.innerHTML = '<tr class="dt-empty"><td colspan="' + cols.length + '">Ошибка загрузки</td></tr>';
    } finally {
      loading = false;
      wrap.classList.remove('dt-loading');
    }
  }

  // пагинация
  if (pager) {
    pager.addEventListener('click', e => {
      const btn = e.target.closest('[data-p]');
      if (!btn || btn.disabled) return;
      const p = parseInt(btn.dataset.p, 10);
      if (p >= 1 && p <= state.pages) {
        state.page = p;
        load(false);
      }
    });
  }

  // сортировка
  wrap.querySelectorAll('th.dt-sortable').forEach(th => {
    th.addEventListener('click', () => {
      const key = th.dataset.key;
      if (state.orderBy === key) {
        state.order = state.order === 'asc' ? 'desc' : 'asc';
      } else {
        state.orderBy = key;
        state.order = 'desc';
      }
      load(true);
    });
  });

  // поиск (debounce)
  if (search) {
    search.addEventListener('input', () => {
      clearTimeout(debounceTimer);
      debounceTimer = setTimeout(() => {
        state.search = search.value.trim();
        load(true);
      }, 300);
    });
  }

  // первая отрисовка пагинации (данные уже с PHP)
  renderPager();
  setSortUI();
})();
</script>
JS;
    }
}