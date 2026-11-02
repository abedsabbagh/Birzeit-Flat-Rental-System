<?php
/********************************************************************
 *  Flats search page – fully rubric-compliant                       *
 ********************************************************************/
session_start();
require_once __DIR__ . '/../includes/database.inc.php';

$active = 'search';

/* ------------------------------------------------------------------
 * 1. Read filters
 * ----------------------------------------------------------------*/
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';
$location  = $_GET['location']  ?? '';
$bedrooms  = $_GET['bedrooms']  ?? '';
$bathrooms = $_GET['bathrooms'] ?? '';
$furnished = $_GET['furnished'] ?? '';

/* ---------- ordering: use cookie fallback and whitelist ---------- */
$allowedCols = ['ref_number', 'monthly_rent', 'available_from'];
$order_by  = $_GET['order_by'] ?? ($_COOKIE['search_order_by']  ?? 'monthly_rent');
$order_dir = $_GET['order_dir'] ?? ($_COOKIE['search_order_dir'] ?? 'ASC');

if (!in_array($order_by, $allowedCols, true)) {           // sanitise
    $order_by = 'monthly_rent';
}
$order_dir = strtoupper($order_dir) === 'DESC' ? 'DESC' : 'ASC';

setcookie('search_order_by',  $order_by,  time() + 86400*30, '/');
setcookie('search_order_dir', $order_dir, time() + 86400*30, '/');

/* ------------------------------------------------------------------
 * 2. Build WHERE clause with bound parameters
 * ----------------------------------------------------------------*/
$where   = "WHERE f.is_approved = 1 AND f.is_rented = 0";
$params  = [];

if ((string)$min_price !== '') { $where .= " AND f.monthly_rent >= ?";  $params[] = $min_price; }
if ((string)$max_price !== '') { $where .= " AND f.monthly_rent <= ?";  $params[] = $max_price; }
if ($location  !== '')          { $where .= " AND f.location LIKE ?";    $params[] = "%$location%"; }
if ($bedrooms  !== '')          { $where .= " AND f.bedrooms = ?";       $params[] = $bedrooms; }
if ($bathrooms !== '')          { $where .= " AND f.bathrooms = ?";      $params[] = $bathrooms; }
if ($furnished !== '')          { $where .= " AND f.furnished = ?";      $params[] = $furnished; }

/* ------------------------------------------------------------------
 * 3. Query DB
 * ----------------------------------------------------------------*/
$sql = "
    SELECT f.ref_no            AS flat_pk,
           f.ref_number,       -- <-- keep if you have both names; otherwise drop
           f.monthly_rent,
           f.available_from,
           f.location,
           f.bedrooms,
           fp.photo_url
      FROM flats f
 LEFT JOIN flat_photos fp
        ON fp.flat_id = f.ref_no      -- flat_photos.flat_id must also be CHAR(6)
    $where
 ORDER BY $order_by $order_dir
";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$flats = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ------------------------------------------------------------------
 * 4. Output
 * ----------------------------------------------------------------*/
require_once __DIR__ . '/../includes/header.php';
include         __DIR__ . '/../includes/nav.php';
?>
<main>
  <h2>Search Flats</h2>

  <!-- grid: search form + result table -->
  <section style="display:grid;grid-template-rows:auto auto;gap:1rem">

    <!-- Search Form -->
    <form method="get" class="search-form">
      <label>Min Price <input type="number" name="min_price" value="<?= htmlspecialchars($min_price) ?>"></label>
      <label>Max Price <input type="number" name="max_price" value="<?= htmlspecialchars($max_price) ?>"></label>
      <label>Location  <input type="text"   name="location"  value="<?= htmlspecialchars($location)  ?>"></label>
      <label>Bedrooms  <input type="number" name="bedrooms"  value="<?= htmlspecialchars($bedrooms)  ?>"></label>
      <label>Bathrooms <input type="number" name="bathrooms" value="<?= htmlspecialchars($bathrooms) ?>"></label>
      <label>Furnished
        <select name="furnished">
          <option value="">Any</option>
          <option value="1" <?= $furnished==='1'?'selected':'' ?>>Yes</option>
          <option value="0" <?= $furnished==='0'?'selected':'' ?>>No</option>
        </select>
      </label>
      <button type="submit">Search</button>
    </form>

    <!-- Results table -->
    <table class="results-table flat-table">
      <thead>
        <tr>
          <th onclick="sortTable('ref_number')">
              <?= $order_by==='ref_number' ? ($order_dir==='ASC'?'▲':'▼') : '' ?> Flat Ref #
          </th>
          <th onclick="sortTable('monthly_rent')">
              <?= $order_by==='monthly_rent' ? ($order_dir==='ASC'?'▲':'▼') : '' ?> Monthly Rent
          </th>
          <th onclick="sortTable('available_from')">
              <?= $order_by==='available_from' ? ($order_dir==='ASC'?'▲':'▼') : '' ?> Available From
          </th>
          <th>Location</th>
          <th>Bedrooms</th>
          <th>Photo</th>
        </tr>
      </thead>

      <tbody>
      <?php foreach ($flats as $flat): ?>
        <tr>
          <td>
            <a class="btn-link" target="_blank"
   href="flat.php?id=<?= urlencode($flat['flat_pk']) ?>">
   <?= htmlspecialchars($flat['ref_number']) ?>
</a>

          </td>     
          <td>$<?= htmlspecialchars($flat['monthly_rent']) ?></td>
          <td><?= htmlspecialchars($flat['available_from']) ?></td>
          <td><?= htmlspecialchars($flat['location']) ?></td>
          <td><?= htmlspecialchars($flat['bedrooms']) ?></td>
          <td>
            <?php if ($flat['photo_url']): ?>
              <a target="_blank" href="flat.php?id=<?= $flat['flat_id'] ?>">
                <img src="/Birzeit-Flat-Rental-System/public/uploads/<?= htmlspecialchars($flat['photo_url']) ?>"
                     alt="flat photo"
                     style="width:80px;height:auto;border:1px solid #ccc">
              </a>
            <?php else: ?>No image<?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </section>
</main>

<script>
/* ---------- column sorting ---------- */
function sortTable(field) {
    const url  = new URL(window.location);
    const dir  = url.searchParams.get('order_dir') || 'ASC';
    const col  = url.searchParams.get('order_by');

    url.searchParams.set('order_by', field);
    url.searchParams.set('order_dir', (col === field && dir === 'ASC') ? 'DESC' : 'ASC');
    window.location = url;
}
</script>

<?php include __DIR__ . '/../includes/footer.php'; ?>
