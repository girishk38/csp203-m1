from scrap import HT, NDTV ,TOI, IDN
from newspaper import Article
import pymysql
from datetime import datetime
import unicodedata


class artAdd:

    def __init__(self, urls):
        self.articles = [Article(url) for url in urls]

    def get_details(self):
        title = []
        descrp = []
        publish_date = []
        image_url = []
        for article in self.articles:
            try:
                article.download()
                article.parse()
                title.append(unicodedata.normalize('NFKD', article.title).encode('ascii','ignore'))
                descrp.append(unicodedata.normalize('NFKD', article.text).encode('ascii','ignore'))
                publish_date.append(article.publish_date)
                image_url.append(article.top_image)
            except:
                minlen = min(len(title), len(descrp), len(publish_date), len(image_url))
                maxlen = max(len(title), len(descrp), len(publish_date), len(image_url))
                if (maxlen - minlen) == 0:
                    pass
                else:
                    ab = minlen
                    fet = [title, descrp, publish_date, image_url]
                    for _ in range(maxlen - minlen):
                        for f in fet:
                            if(len(f) <= ab):
                                continue
                            else:
                                f.remove(f[-1])
                        
        return title, descrp, publish_date, image_url

def run():

    ht_urls = HT.get_urls()
    ndtv_urls = NDTV.get_urls()
    toi_urls = TOI.get_urls()
    idn_urls = IDN.get_urls()


    urls = []
    urls.extend(ht_urls)
    urls.extend(ndtv_urls)
    urls.extend(toi_urls)
    urls.extend(idn_urls)

    articles = artAdd(urls)

    titles, descrp, time_stamp, image_url = articles.get_details()

    num_articles = len(titles)
    #print(num_articles)

    source = "Hindustan Times"

    db = pymysql.connect("localhost", "root", "12qwaszx", "newsy")

    cursor = db.cursor()

    newArticles = 0

    for i in range(num_articles):
        try:
            if time_stamp[i] == None:
                query = "INSERT INTO `articles`(`Title`, `description`, `source`, `time_stamp`, `link`, `image_url`) values(\"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\")" %(pymysql.escape_string(titles[i].decode("utf-8")), pymysql.escape_string(descrp[i].decode("utf-8")),source, datetime.now().strftime('%Y-%m-%d %H:%M:%S') ,urls[i], image_url[i])
            else:
                query = "INSERT INTO `articles`(`Title`, `description`, `source`, `time_stamp`, `link`, `image_url`) values(\"%s\", \"%s\", \"%s\", \"%s\", \"%s\", \"%s\")" %(pymysql.escape_string(titles[i].decode("utf-8")),pymysql.escape_string(descrp[i].decode("utf-8")), source,time_stamp[i].strftime('%Y-%m-%d %H:%M:%S'), urls[i], image_url[i])
            cursor.execute(query)
            db.commit()
            newArticles += 1
        except pymysql.MySQLError as e:
            code, *msg = e.args
            if code == 1062:
                pass
            else:
                print("ERROR CODE: {} | {}".format(code, *msg))

    print("{} new articles".format(newArticles))
    db.close()