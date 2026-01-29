<?php
header('Content-Type: application/json; charset=utf-8');

$BOT_TOKEN = "";
$CHAT_ID   = "6401716604";

$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$comment = trim($_POST['comment'] ?? '');

if ($phone === '') {
  http_response_code(400);
  echo json_encode(['ok' => false, 'error' => 'Телефон обязателен']);
  exit;
}

$text = "📦 *Заявка на расчёт*\n"
      . "👤 Имя: " . ($name !== '' ? $name : '—') . "\n"
      . "📞 Телефон: *{$phone}*\n"
      . "💬 Комментарий: " . ($comment !== '' ? $comment : '—');

$url = "https://api.telegram.org/bot";

$post = [
  'chat_id' => $CHAT_ID,
  'text' => $text,
  'parse_mode' => 'Markdown'
];

$ch = curl_init($url);
curl_setopt_array($ch, [
  CURLOPT_POST => true,
  CURLOPT_POSTFIELDS => http_build_query($post),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 10,
]);

$response = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http >= 400 || $response === false) {
  http_response_code(500);
  echo json_encode(['ok' => false, 'error' => 'Telegram error', 'details' => $response]);
  exit;
}

echo json_encode(['ok' => true]);
