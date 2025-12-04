<?php
$telegramBotToken = getenv("TELEGRAM_BOT_TOKEN");
$telegramChatId   = getenv("TELEGRAM_CHAT_ID");

$feeds = [
    "https://news.google.com/rss?hl=en-NG&gl=NG&ceid=NG:en",
    "https://rss.cnn.com/rss/edition.rss",
    "https://feeds.bbci.co.uk/news/rss.xml"
];

function getViralHeadlines($feeds) {
    $headlines = [];
    foreach ($feeds as $feed) {
        $xml = @simplexml_load_file($feed);
        if (!$xml) continue;
        foreach ($xml->channel->item as $item) {
            $title = (string)$item->title;
            $link  = (string)$item->link;
            $headlines[] = "🔥 *$title* \n$link";
        }
    }
    return array_slice($headlines, 0, 5);
}

function sendToTelegram($botToken, $chatId, $message) {
    $url = "https://api.telegram.org/bot$botToken/sendMessage";
    $data = [
        'chat_id'    => $chatId,
        'text'       => $message,
        'parse_mode' => 'Markdown'
    ];
    file_get_contents($url . "?" . http_build_query($data));
}

$viral = getViralHeadlines($feeds);

if (empty($viral)) {
    sendToTelegram($telegramBotToken, $telegramChatId, "⚠️ No viral news found.");
    exit;
}

foreach ($viral as $news) {
    sendToTelegram($telegramBotToken, $telegramChatId, $news);
}

echo "Broadcast sent!";
