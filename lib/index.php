<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>SCAU图书馆搜索</title>
    <link rel="stylesheet" href="http://res.wx.qq.com/open/libs/weui/1.1.2/weui.min.css"/>
    <script type="text/javascript" src="http://res.wx.qq.com/open/libs/weuijs/1.1.2/weui.min.js"></script>
    <script src="vue.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>
<body>
<div class="weui-search-bar" id="search_bar">
    <form class="weui-search-bar__form">
        <div class="weui-search-bar__box" id="search_bar_input">
            <i class="weui-icon-search"></i>
            <input type="text" hidden="hidden">
            <input v-model="searchContent" @submit="getSearchContent" @keyup.enter="getSearchContent"
                   @blur="getSearchContent" type="search" class="weui-search-bar__input" id="search_input"
                   placeholder="搜索"/>
            <a href="javascript:" class="weui-icon-clear" id="search_clear"></a>
        </div>
        <label for="search_input" class="weui-search-bar__label" id="search_text">
            <i class="weui-icon-search"></i>
            <span>搜索</span>
        </label>
    </form>
    <a href="javascript:" class="weui-search-bar__cancel-btn" id="search_cancel">取消</a>
</div>
        <div id="app-book">
            <ol>
                <books
                        v-for="book in books"
                        v-bind:book="book"
                        v-bind:href="book.url"
                        v-bind:key="book.id">
                </books>
            </ol>
            <div v-if="hasMorePages && !loading" @click="loadMore" class="weui-loadmore">
                <!--        <i class="weui-loading"></i>-->
                <span class="weui-loadmore__tips">加载更多</span>
            </div>
            <div v-if="loading" class="weui-loadmore">
                <i class="weui-loading"></i>
                <span class="weui-loadmore__tips">正在加载</span>
            </div>
            <div v-if="sum == -1 && !loading" class="weui-loadmore weui-loadmore_line">
                <span class="weui-loadmore__tips">请输入搜索内容</span>
            </div>
            <div v-if="sum == 0" class="weui-loadmore weui-loadmore_line">
                <span class="weui-loadmore__tips">暂无数据</span>
            </div>
            <div v-if="!hasMorePages" class="weui-loadmore weui-loadmore_line weui-loadmore_dot">
                <span class="weui-loadmore__tips"></span>
            </div>
        </div>

</body>

<script>
    if (!window.sessionStorage) {
        console.log("浏览器不支持SessionStorage");
    } else {

    }
    weui.searchBar('#search_bar');
    let vm = new Vue({
        el: '#search_bar_input',
        data: {
            searchContent: ''
        },
        methods: {
            getSearchContent() {
                app_book.searchContent = this.searchContent;
            }
        }
    });
    Vue.component('books', {
        props: ['book'],
        template: '<a><li><div class="weui-panel">' +
        '<div class="weui-panel__bd">' +
        '<div class="weui-media-box weui-media-box_text">' +
        '<h4 class="weui-media-box__title">{{book.title}}</h4>' +
        '<ul class="weui-media-box__info">' +
        '<li class="weui-media-box__info__meta">{{book.author}}</li>' +
        '<li class="weui-media-box__info__meta weui-media-box__info__meta_extra">{{book.compress}}</li>' +
        '<li class="weui-media-box__info__meta weui-media-box__info__meta_extra">{{book.index}}</li>' +
        '<li class="weui-media-box__info__meta weui-media-box__info__meta_extra">其它信息</li></ul>' +
        '</div></div></div></li></a>'
    });
    let app_book = new Vue({
        el: '#app-book',
        data: {
            books: [],
            searchContent: '',
            currentPage: 1,
            hasMorePages: false,
            booksPerPage: 20,
            pages: 0,
            sum: -1,
            loading: false,
            tmp: '',
        },
        mounted: function () {
            let self = this;
            console.log('mounted begin');
//            self.loading = true;
//            self.searchPage = self.currentPage;
//            url = '/learnvue/DOM_Parser_test.php?_ijt=a75t7a3nqeuhpv8i73m2bstmp8&method=search&searchContent=';
//            url += self.searchContent;
//            url += '&page=';
//            url += self.searchPage;
//            axios.get(url)
//                .then(function (response) {
//                    new_books = response.data.data[0]['books'];
//                    self.books = self.books.concat(new_books);
//                    self.sum = parseInt(response.data.data[0]['sum']);
//                        console.log(self.sum);
//                    self.pages = self.sum / self.booksPerPage;console.log('pages:'+self.pages);
//                    self.checkPages();
////                    console.log('hasPages:'+self.hasMorePages);
//                }).catch(function (error) {
//                console.log(error);
//            });
            if (sessionStorage.getItem('books')) {
//                console.log("session_data:"+JSON.parse(sessionStorage.getItem('books')));
                self.books = self.books.concat(JSON.parse(sessionStorage.getItem('books')));
//                console.log("book_data:"+self.books);
                self.sum = sessionStorage.getItem('sum');
                self.currentPage = sessionStorage.getItem('currentPage');
                self.hasMorePages = sessionStorage.getItem('hasMorePages');
                console.log("searchContent:" + sessionStorage.getItem('searchContent'));
                self.searchContent = sessionStorage.getItem('searchContent');
            }
//            this.loadMore();
//            console.log('done');
            console.log('mounted end');
            self.loading = false;

        },
        computed: {},
        methods: {
            loadMore: function () {
                console.log('----loadMore begin');
                let self = this;
                self.loading = true;
                //loading
                searchPage = ++self.currentPage;
//                url = '/test/lib/DOM_Parser_test.php?_ijt=a75t7a3nqeuhpv8i73m2bstmp8&searchContent=';
//                url += self.searchContent;
//                url += '&page=';
//                url += searchPage;
//                axios.get(url)
//                    .then((response) => {
////                        console.log(response.data.data[0]['sum']);
////                        console.log(response.data.data[0]['books']);
//                        new_books = response.data.data[0]['books'];
////                        console.log(response.data.data);
//                        self.books = self.books.concat(new_books);
//                        self.sum = parseInt(response.data[0]);
////                        console.log(getsum);
//                        self.checkPages();
//                        self.loading = false;
//                    }).catch(function (error) {
//                    console.log(error);
//                });
                self.http_get();
                //end loading
            },
            checkPages: function () {
                console.log('----checked-----');
                let self = this;
                console.log('hasPagesBeforeChecked:' + self.hasMorePages);
                self.hasMorePages = self.pages > self.currentPage;
                console.log('hasPagesAfterChecked:' + self.hasMorePages);
                console.log('----checked-----');
//                console.log(this.pages);
//                console.log(this.currentPage);
//                console.log(this.hasMorePages);
            },
            reload: function () {
                console.log('-----reload begin-----');
                let self = this;
                self.books = [];
                self.currentPage = 1;
                self.loading = true;
                //loading
                self.http_get();
                //end loading
//                console.log('-----reload end-----')
            },
            http_get: function () {
                let self = this;
                searchPage = self.currentPage;
                url = '/learnvue/DOM_Parser_test.php?_ijt=a75t7a3nqeuhpv8i73m2bstmp8&method=search&searchContent=';
                url += self.searchContent;
                url += '&page=';
                url += searchPage;
                axios.get(url)
                    .then((response) => {
//                        console.log(response.data.data[0]['sum']);
                        console.log(response.data.data[0]['books']);
                        new_books = response.data.data[0]['books'];
//                        console.log(response.data.data);
                        self.books = self.books.concat(new_books);
                        self.sum = parseInt(response.data.data[0]['sum']);
                        self.pages = self.sum / self.booksPerPage;
//                        console.log('sum:'+self.sum);
//                        console.log('current'+self.currentPage);
//                        console.log('pages'+self.pages);
                        self.checkPages();
//                        console.log('hasPages:'+self.hasMorePages);
                        self.loading = false;

                    }).catch(function (error) {
                    console.log(error);
                });
            }
        },
        watch: {
            searchContent: function () {
                console.log('searchContent changed:' + this.searchContent + "and inSession:" + sessionStorage.getItem('searchContent'));
                if (sessionStorage.getItem('searchContent') !== this.searchContent) {
                    this.reload();
                }
            },
            books: function () {
                let self = this;
                sessionStorage.setItem('books', JSON.stringify(this.books));
                sessionStorage.setItem('sum', self.sum);
                sessionStorage.setItem('hasMorePages', self.hasMorePages);
                sessionStorage.setItem('currentPage', self.currentPage);
                sessionStorage.setItem('searchContent', self.searchContent);
                console.log("----session changed!");
                console.log(self.searchContent);
            }
        }

    });
    //    axios.get("http://localhost:63342/test/lib/DOM_Parser_test.php?_ijt=a75t7a3nqeuhpv8i73m2bstmp8&method=getBooks&searchContent=laravel&page=1")
    //        .then(function (response) {
    //            console.log( response.data.data[0]['sum']);
    //            console.log( response.data.data[0]['books']);
    //            new_books = response.data.data[0]['books'];
    //            console.log( response.data.data);
    //            app_book.books = app_book.books.concat(new_books);
    //            app_book.sum = parseInt(response.data.data[0]['sum']);
    ////                        console.log(getsum);
    //        }).catch(function (error) {
    //        console.log(error);
    //    });

</script>
</html>