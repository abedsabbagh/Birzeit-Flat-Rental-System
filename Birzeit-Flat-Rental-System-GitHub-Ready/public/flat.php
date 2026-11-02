<?php
session_start();
require_once __DIR__ . '/../includes/database.inc.php';

$active = ''; 

$flat_id = $_GET['id'] ?? '';
if (!$flat_id) {
    header('Location: search.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT *
    FROM flats
    WHERE id = ? AND is_approved = 1
");
$stmt->execute([ $flat_id ]);
$flat = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$flat) {
    echo "<main><h2>Flat not found or not approved.</h2></main>";
    include __DIR__ . '/../includes/footer.php';
    exit;
}

$stmt = $pdo->prepare("
    SELECT photo_url
    FROM flat_photos
    WHERE flat_id = ?
    LIMIT 3
");
$stmt->execute([ $flat_id ]);
$photos = $stmt->fetchAll(PDO::FETCH_COLUMN);

$stmt = $pdo->prepare("
    SELECT title, description, url
    FROM flat_marketing
    WHERE flat_id = ?
");
$stmt->execute([ $flat_id ]);
$marketing = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/header.php';
include    __DIR__ . '/../includes/nav.php';
?>

<main>
  <h2>Flat Details: <?= htmlspecialchars($flat['ref_number']) ?></h2>

  <div class="flatcard">
    <!-- the pictures -->
    <div style="flex: 1;">
      <?php foreach ($photos as $photo): ?>
        <figure>
          <img src="/Birzeit-Flat-Rental-System/public/uploads/<?= htmlspecialchars($photo) ?>" alt="Flat Photo" style="width: 100%; max-width: 300px; height: auto; margin-bottom: .5rem;">
        </figure>
      <?php endforeach; ?>
    </div>

    <!-- details -->
    <div style="flex: 2; padding-left: 1rem;">
      <p><strong>Address:</strong> <?= htmlspecialchars($flat['address_street']) ?>, <?= htmlspecialchars($flat['location']) ?></p>
      <p><strong>Price:</strong> $<?= htmlspecialchars($flat['monthly_rent']) ?> / month</p>
      <p><strong>Rental Conditions:</strong> <?= nl2br(htmlspecialchars($flat['rental_conditions'])) ?></p>
      <p><strong>Bedrooms:</strong> <?= htmlspecialchars($flat['bedrooms']) ?></p>
      <p><strong>Bathrooms:</strong> <?= htmlspecialchars($flat['bathrooms']) ?></p>
      <p><strong>Size:</strong> <?= htmlspecialchars($flat['size_sqm']) ?> sqm</p>
      <p><strong>Heating:</strong> <?= $flat['heating'] ? 'Yes' : 'No' ?></p>
      <p><strong>Air-conditioning:</strong> <?= $flat['air_conditioning'] ? 'Yes' : 'No' ?></p>
      <p><strong>Access Control:</strong> <?= $flat['access_control'] ? 'Yes' : 'No' ?></p>
      <p><strong>Features:</strong>
        <?php
        $features = [];
        if ($flat['parking']) $features[] = 'Parking';
        if ($flat['backyard']) $features[] = 'Backyard';
        if ($flat['playground']) $features[] = 'Playground';
        if ($flat['storage']) $features[] = 'Storage';
        echo implode(', ', $features) ?: 'None';
        ?>
      </p>

      <!-- Side nav links -->
      <div style="margin-top: 1rem;">
        <a href="viewing.php?flat=<?= $flat_id ?>" class="btn-ref">Request Flat Viewing Appointment</a><br><br>
        <a href="rent.php?flat=<?= $flat_id ?>" class="btn-ref">Rent this Flat</a>
      </div>
    </div>
  </div>

  <!-- Aside -->
  <aside style="float: right; width: 250px; margin-top: 1rem; border: 1px solid #ccc; padding: .5rem;">
    <h3>Nearby Landmarks</h3>
    <?php foreach ($marketing as $item): ?>
      <p>
        <strong><?= htmlspecialchars($item['title']) ?></strong><br>
        <?= nl2br(htmlspecialchars($item['description'])) ?><br>
        <?php if ($item['url']): ?>
          <a href="<?= htmlspecialchars($item['url']) ?>" target="_blank">More info</a>
        <?php endif; ?>
      </p>
    <?php endforeach; ?>
  </aside>

</main>

<?php include __DIR__ . '/../includes/footer.php'; ?>
