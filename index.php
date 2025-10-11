<!DOCTYPE html>
<html>
<head>
  <title>Bible Verse Finder</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="outer-box">
    <div class="container">
      <h2>Bible Verse Finder</h2>

      <form method="GET" class="verse-form">
        <div class="form-group">
          <label>Book:</label>
          <input type="text" name="book" placeholder="e.g. John" required>
          <small>Example: John</small>
        </div>

        <div class="form-group">
          <label>Chapter:</label>
          <input type="number" name="chapter" placeholder="e.g. 3" required>
          <small>Example: 3</small>
        </div>

        <div class="form-group">
          <label>Verse:</label>
          <input type="text" name="verse" placeholder="e.g. 16 or 16-18" required>
          <small>Example: 16 or 16-18</small>
        </div>

        <div class="form-group">
          <label>Translation:</label>
          <select name="translation">
            <option value="kjv">King James Version (KJV)</option>
            <option value="web">World English Bible (WEB)</option>
            <option value="asv">American Standard Version (ASV)</option>
            <option value="bbe">Bible in Basic English (BBE)</option>
          </select>
        </div>

        <button type="submit">Submit</button>
      </form>

      <div class="result-box">
        <?php
        if (isset($_GET['book'], $_GET['chapter'], $_GET['verse'])) {
            $book = urlencode($_GET['book']);
            $chapter = urlencode($_GET['chapter']);
            $verse = urlencode($_GET['verse']);
            $translation = urlencode($_GET['translation'] ?? 'kjv');

            $url = "https://bible-api.com/{$book}+{$chapter}:{$verse}?translation={$translation}";
            $response = @file_get_contents($url);

            if ($response === FALSE) {
                echo "<p class='error'>❌ Verse not found or invalid passage.</p>";
            } else {
                $data = json_decode($response, true);
                if (isset($data['error'])) {
                    echo "<p class='error'>❌ " . htmlspecialchars($data['error']) . "</p>";
                } else {
                    echo "<h3>" . htmlspecialchars($data['reference']) . "</h3>";
                    echo "<p id='verseText'>" . nl2br(htmlspecialchars($data['text'])) . "</p>";
                    echo "<p><b>Translation:</b> " . htmlspecialchars($data['translation_name']) . "</p>";
                    echo "<button class='copy-btn' onclick='copyVerse()'>Copy Verse</button>";
                }
            }
        } else {
            echo "<p class='placeholder'>Enter a Bible verse above to display it here.</p>";
        }
        ?>
      </div>

      <div class="credit">
        <small>API by <a href='https://bible-api.com' target='_blank'>bible-api.com</a></small>
      </div>
    </div>
  </div>

  <script>
    function copyVerse() {
      const text = document.getElementById("verseText")?.innerText;
      if (text) {
        navigator.clipboard.writeText(text);
        alert("✅ Verse copied to clipboard!");
      }
    }
  </script>
</body>
</html>
