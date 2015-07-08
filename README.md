# taipei-mrt-table
把 http://www.metro.taipei/ct.asp?xItem=78479152&CtNode=70089&mp=122035 內各站時刻表的 PDF 抓出班次時間的程式及資料

使用方式
=========
* php get-pdf-list.php > pdf.csv   # 產生出各站各方向的 PDF 檔名的對照表
* php download-all.php    # 把 pdf.csv 裡面的 pdf 全部抓到 pdfs/ 資料夾下
* php pdf-to-table.php > time-table.csv   # 把 pdf.csv 和 pdfs/ 的 PDF 的時刻表抓出來並匯出

需要軟體
========
* xpdf 的 pdftotext

License
=======
所有程式碼使用 BSD License 
