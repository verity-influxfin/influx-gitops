<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:Generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemap';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // delete last line of file and save
        $file = file('public/upload/sitemap.xml');
        array_pop($file);
        file_put_contents('public/upload/sitemap.xml', $file);
        // add new xml data to file
        $file = fopen('public/upload/sitemap.xml', 'a');
        // get date yesterday
        $date_search = date('Y-m-d', strtotime('-1 day'));
        // 取得一天內的所有文章(最新消息，小學堂文章)
        $news = DB::table('news')->select(['id', 'created_at'])->where('created_at', '>=', $date_search)->get();
        $knowledge_article = DB::table('knowledge_article')->select(['id', 'created_at'])->where('created_at', '>=', $date_search)->get();
        // for news
        foreach ($news as $n) {
            $date = gmdate('Y-m-d\TH:i:s', strtotime($n->created_at));
            $content = '<url>'.PHP_EOL;
            $content .= '  <loc>https://www.influxfin.com/articlepage?q=news-' . $n->id . '</loc>'.PHP_EOL;
            $content .= '  <lastmod>' . $date . '</lastmod>'.PHP_EOL;
            $content .= '  <changefreq>weekly</changefreq>'.PHP_EOL;
            $content .= '  <priority>0.6</priority>'.PHP_EOL;
            $content .= '</url>'.PHP_EOL;
            fwrite($file, $content);
        }
        // for knowledge article
        foreach ($knowledge_article as $k) {
            $date = gmdate('Y-m-d\TH:i:s', strtotime($k->created_at));
            $content = '<url>'.PHP_EOL;
            $content .= '  <loc>https://www.influxfin.com/articlepage?q=knowledge-' . $k->id . '</loc>'.PHP_EOL;
            $content .= '  <lastmod>' . $date . '</lastmod>'.PHP_EOL;
            $content .= '  <changefreq>weekly</changefreq>'.PHP_EOL;
            $content .= '  <priority>0.6</priority>'.PHP_EOL;
            $content .= '</url>'.PHP_EOL;
            fwrite($file, $content);
        }
        // close file
        $lastline = '</urlset>'.PHP_EOL;
        fwrite($file, $lastline);
        fclose($file);
        return Command::SUCCESS;
    }
}
