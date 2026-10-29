<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $catalog = [
            'A&W' => [
                'NEW! 2 Curry Aroma Chicken, Rice, RB & FREE! AWniversary Mug' => [
                    'desc' => 'Paket spesial 2 potong ayam goreng Aroma Chicken krispi berlumur saus kari rempah khas A&W, nasi putih hangat, minuman segar A&W Root Beer (RB), plus mug AWniversary edisi terbatas.',
                    'note' => 'Kemas ayam dalam box karton A&W berpori agar kerenyahan kulit terjaga. Root Beer di-seal rapat dengan lid anti-tumpah, sertakan sedotan dan mug dalam box terpisah.',
                ],
                'NEW! Curry Aroma Chicken, Rice, Chic Soup, Chic Chunks, RB & FREE! AWniversary Mug' => [
                    'desc' => 'Paket komplit 1 potong Curry Aroma Chicken krispi, nasi pulen, sup ayam bening gurih hangat, chicken chunks renyah, dan minuman segar Root Beer plus merchandise mug AWniversary.',
                    'note' => 'Sup ayam dikemas dalam cup sup tahan panas ber-seal ganda anti bocor. Root Beer diberi tutup kencang.',
                ],
                'NEW! Curry Double Beef Burger, Fries, RB & FREE! AWniversary Mug' => [
                    'desc' => 'Burger daging sapi ganda panggang juicy dengan lumuran saus kari aromatik, keju leleh, kentang goreng renyah (fries), segelas Root Beer dingin segar, dan free mug AWniversary.',
                    'note' => 'Bungkus burger rapi dengan wax paper anti-minyak, kentang goreng jangan ditutup terlalu rapat agar tetap krispi.',
                ],
                'NEW! Curry Chicken Sandwich, Fries, RB & FREE! AWniversary Mug' => [
                    'desc' => 'Sandwich roti wijen lembut dengan fillet ayam goreng renyah bersaus kari gurih sedap, selada segar, kentang goreng, dan segelas Root Beer legendaris plus mug AWniversary.',
                    'note' => 'Pastikan saus kari tidak meluber ke luar bungkus burger, tata fries tegak lurus di paper bag.',
                ],
                'NEW! Curry Aroma Chicken' => [
                    'desc' => 'Potongan ayam goreng khas A&W yang renyah keemasan di luar dan juicy di dalam, diselimuti bumbu kari rempah gurih manis yang kaya rasa.',
                    'note' => 'Tiriskan minyak dengan sempurna, masukkan ke dalam kantong kertas anti-minyak A&W.',
                ],
                'NEW! Curry Fries' => [
                    'desc' => 'Kentang goreng crinkle-cut khas A&W yang garing bertabur saus kari spesial yang gurih, harum, dan nagih.',
                    'note' => 'Goreng fresh saat dipesan, pisahkan saus kari dalam cup cocolan terpisah jika untuk take-away jarak jauh.',
                ],
                'NEW! Tom Yum Soup' => [
                    'desc' => 'Sup kuah asam pedas khas Thailand beraroma serai dan daun jeruk yang segar menghangatkan tenggorokan, berisi potongan bakso dan jamur kenyal.',
                    'note' => 'Kuah sup disajikan mendidih, gunakan cup sup tahan panas ber-seal anti rembes khusus take-away kabin.',
                ],
                'NEW! Tom Yum Soup, Aroma Chic, Rice & RB' => [
                    'desc' => 'Paket mantap kombinasi sup Tom Yum segar pedas asam, 1 potong Aroma Chicken krispi, nasi hangat, dan segelas Root Beer dingin menyegarkan.',
                    'note' => 'Kemas sup tom yum dan Root Beer dengan seal cup kencang. Berikan sendok plastik dan tisu makan.',
                ],
            ],

            'EXPAT' => [
                'Toast and Spread' => [
                    'desc' => 'Dua lembar roti sourdough panggang bertekstur chewy renyah dengan mentega Australia pilihan dan pilihan selai buah artisan berkualitas.',
                    'note' => 'Panggang hingga kecokelatan emas, kemas selai dan butter dalam cup mini terpisah.',
                ],
                'Smash Avocado Sourdough' => [
                    'desc' => 'Roti sourdough artisan renyah dengan topping alpukat tumbuk segar creamy, keju feta gurih, taburan biji chia, dan perasan jeruk lemon segar.',
                    'note' => 'Kemas dalam box bento kraft ramah lingkungan, pastikan alpukat tidak tergencet tutup box.',
                ],
                'Ricotta Pancake Stack' => [
                    'desc' => 'Tumpukan pancake keju ricotta lembut empuk beraroma vanila harum, disajikan dengan buah berry segar, mentega madu, dan sirup maple murni.',
                    'note' => 'Sirup maple dan mentega dikemas dalam wadah saus terpisah, beri garnish buah berry segar di atas pancake.',
                ],
                'Baked Bowl' => [
                    'desc' => 'Mangkuk sarapan sehat panggang berisi quinoa hangat, telur mata sapi, alpukat iris, bayam tumis, dan saus hollandaise lembut beraroma butter.',
                    'note' => 'Kemas dalam paper bowl tebal dengan penutup transparan, pastikan kuning telur tidak pecah saat transit.',
                ],
                'Granola Panna Cotta' => [
                    'desc' => 'Panna cotta vanila sutra lembut dingin bertabur granola gandum renyah panggang, madu murni, dan potongan buah segar kaya serat.',
                    'note' => 'Sajikan dingin dari chiller showcase, pasang tutup cup kencang dan beri sendok dessert.',
                ],
                'Smoothie Bowl' => [
                    'desc' => 'Mangkuk smoothie kental dari pure buah naga, pisang, dan buah beri segar, berhiaskan irisan kiwi, kelapa parut panggang, dan biji chia.',
                    'note' => 'Sajikan beku pas agar tidak cepat mencair selama proses penyerahan pesanan di gate bandara.',
                ],
                'Scrambled Chilli Prawn Eggs' => [
                    'desc' => 'Telur orak-arik krimi super lembut di atas roti sourdough dengan topping udang laut segar tumis minyak cabai aromatik yang gurih pedas manis.',
                    'note' => 'Udang ditumis harum, taburi daun kucai segar di atas telur, kemas rapat.',
                ],
                'Eggs Benedict' => [
                    'desc' => 'Dua butir poached egg setengah matang lumer di atas English muffin renyah dengan irisan ham sapi asap dan siraman saus hollandaise gurih asam segar.',
                    'note' => 'Saus hollandaise disiram tipis di atas telur, bungkus dengan wadah kokoh agar posisi telur tetap stabil.',
                ],
                'Everything on a Plate' => [
                    'desc' => 'Menu sarapan ala Barat super komplit berisi sourdough toast, dua butir telur, sosis sapi panggang, jamur tumis, tomat ceri panggang, dan hash brown garing.',
                    'note' => 'Tata seluruh komponen dengan rapi dalam box bersekat besar, sertakan pisau dan garpu makan.',
                ],
                'Everything in a Bowl' => [
                    'desc' => 'Mangkuk nutrisi lengkap berisi salad hijau segar, alpukat iris, telur rebus setengah matang, edamame gurih, jagung manis, dan saus wijen sangrai Jepang.',
                    'note' => 'Dressing wijen sangrai dipisah dalam botol/cup mini saus agar sayuran tetap renyah garing.',
                ],
            ],

            'SKY BEANS' => [
                'SkyBeans Coffee' => [
                    'desc' => 'Kopi susu khas Sky Beans dengan perpaduan espresso house-blend pilihan, susu segar gurih, dan sentuhan sirup gula aren lembut beraroma harum.',
                    'note' => 'Tutup cup dengan mesin sealer atau lid anti-tumpah, sediakan sedotan ramah lingkungan.',
                ],
                'Butterscotch' => [
                    'desc' => 'Minuman kopi susu creamy dengan sirup butterscotch manis karamel mentega gurih yang memanjakan lidah saat transit santai.',
                    'note' => 'Aduk merata sirup di dasar cup sebelum diberi es dan susu, pastikan seal rapat.',
                ],
                'Caramel' => [
                    'desc' => 'Paduan kopi espresso seimbang dengan susu dingin dan siraman saus karamel lezat yang manis lembut aromatik.',
                    'note' => 'Berikan drizzle saus karamel di dinding cup untuk visual menarik.',
                ],
                'Shallen Palm Sugar' => [
                    'desc' => 'Kopi susu aren otentik racikan Shallen dengan aroma nira aren murni dan tekstur creamy kental yang menyegarkan dahaga.',
                    'note' => 'Takaran gula aren disesuaikan standar, pasang seal anti tumpah untuk penumpang pesawat.',
                ],
                'Milky' => [
                    'desc' => 'Minuman susu segar dingin creamy vanila yang manis gurih menenangkan, cocok bagi penumpang yang ingin minuman non-kopi.',
                    'note' => 'Sajikan dingin dengan takaran es yang pas.',
                ],
                'Peach Black' => [
                    'desc' => 'Kopi hitam Americano dingin dipadukan sari buah persik harum manis segar yang memberikan sensasi fruity menyegarkan.',
                    'note' => 'Shake kopi dan sari persik dengan shaker sebelum dituangkan ke atas es.',
                ],
                'Espresso' => [
                    'desc' => 'Ekstrak seduhan biji kopi pilihan pekat dengan lapisan crema emas tebal beraroma kuat untuk dorongan energi sebelum terbang.',
                    'note' => 'Sajikan fresh dari mesin espresso portafilter, tutup cangkir dengan lid mini pelindung panas.',
                ],
                'Americano' => [
                    'desc' => 'Seduhan double shot espresso pekat yang dilarutkan air mineral murni, menghasilkan rasa kopi yang bersih, bold, dan menyegarkan.',
                    'note' => 'Berikan sleeve karton pelindung panas jika panas, atau seal anti bocor jika dipesan dingin.',
                ],
                'Piccolo' => [
                    'desc' => 'Kopi espresso berpadu sedikit steamed milk hangat dengan perbandingan kopi yang mantap dan rasa creamy tipis lembut.',
                    'note' => 'Sajikan hangat dalam cup 4oz, tutup rapat.',
                ],
                'Flat White' => [
                    'desc' => 'Kopi espresso mantap berpadu susu panas bertekstur microfoam tipis velvety khas Australia yang lembut di tenggorokan.',
                    'note' => 'Tuang susu secara halus agar microfoam menyatu sempurna dengan crema kopi.',
                ],
                'Cappuccino' => [
                    'desc' => 'Perpaduan seimbang espresso kaya rasa, susu panas, dan busa susu tebal lembut bertabur bubuk kakao nikmat.',
                    'note' => 'Taburi cokelat bubuk tipis di atas busa susu sebelum dipasang penutup lid.',
                ],
            ],

            'SARI BUNDO' => [
                'Paket Rendang' => [
                    'desc' => 'Paket nasi pulen khas Minang dengan sepotong rendang daging sapi empuk hitam pekat berlumur bumbu kelapa rempah otentik, sayur daun singkong, gulai nangka, dan sambal hijau.',
                    'note' => 'Bumbu rendang dilumuri tebal di atas daging, kemas rapi dalam bento box bersekat dan lapisi daun pisang.',
                ],
                'Paket Dendeng Balado' => [
                    'desc' => 'Paket nasi Padang lengkap dengan irisan tipis daging dendeng sapi goreng garing renyah berbalut sambal balado cabai merah ulek kasar gurih segar.',
                    'note' => 'Pisahkan sambal balado agar dendeng tetap garing renyah saat dibawa penumpang ke kabin pesawat.',
                ],
                'Paket Ayam Pop' => [
                    'desc' => 'Paket nasi rames dengan ayam kampung pop gurih lembut tanpa kulit yang dimasak air kelapa, disajikan lengkap dengan saus cabai tomat merah khas ayam pop.',
                    'note' => 'Saus cabai ayam pop dikemas dalam cup saus terpisah bertutup rapat.',
                ],
                'Paket Ayam Gulai' => [
                    'desc' => 'Nasi hangat Padang dengan sepotong ayam empuk yang dimasak dalam kuah gulai santan kuning beraroma kunyit dan serai harum, lengkap dengan lalap dan sambal.',
                    'note' => 'Kuah gulai ayam dikemas rapat agar tidak merembes ke luar kotak nasi.',
                ],
                'Paket Ikan Gulai' => [
                    'desc' => 'Nasi pulen dengan potongan ikan laut segar berbumbu gulai Minang yang gurih pedas asam segar beraroma daun ruku-ruku, disajikan bersama sayur nangka dan sambal hijau.',
                    'note' => 'Masukkan ikan dengan hati-hati agar potongan ikan tetap utuh dan kuah terserap sedap.',
                ],
                'Paket Tunjang' => [
                    'desc' => 'Sajian favorit tunjang (kikil sapi) empuk kenyal gurih dalam siraman kuah gulai santan kental merah kaya rempah, lengkap dengan nasi hangat dan lalapan.',
                    'note' => 'Kemas kuah gulai tunjang kental dengan plastik pembungkus ganda anti rembes.',
                ],
                'Paket Ayam Goreng' => [
                    'desc' => 'Paket nasi Padang dengan ayam goreng gurih berbalut remahan bumbu lengkuas goreng renyah garing, sayur singkong rebus, dan sambal hijau pedas mantap.',
                    'note' => 'Tiriskan minyak ayam goreng dengan sempurna, taburi bumbu remahan lengkuas di atasnya.',
                ],
                'Paket Telur Balado' => [
                    'desc' => 'Menu hemat berselera nasi Padang dengan sebutir telur ayam rebus goreng berlumur sambal balado merah pedas gurih, sayur daun singkong, dan siraman kuah gulai.',
                    'note' => 'Kemas telur balado di bagian kompartemen lauk, sertakan sendok garpu higienis.',
                ],
            ],

            'BEARD PAPA\'S' => [
                'Cheese Cream Puff' => [
                    'desc' => 'Choux pastry renyah garing berisikan krim keju custard lembut dingin yang lumer legit di lidah dengan rasa gurih manis seimbang.',
                    'note' => 'Suntikkan krim custard fresh saat pesanan masuk, masukkan ke dalam kantong khusus Beard Papa\'s satuan.',
                ],
                'Pie Cream Puff Vanilla' => [
                    'desc' => 'Puff pastry berlapis renyah dengan aroma butter wangi, berisikan krim custard vanila asli berbintik vanila pod Madagaskar yang manis dingin lembut.',
                    'note' => 'Pastikan kulit pie tetap garing, taburi gula bubuk halus tipis di atas puff sebelum dikemas.',
                ],
                'Pie Cream Puff Chocolate' => [
                    'desc' => 'Pastry pie garing renyah berlapis dengan isian krim custard cokelat pekat dingin yang manis lumer memanjakan selera.',
                    'note' => 'Isi krim fresh dari chiller, gunakan sarung tangan makanan higienis saat mengemas ke box.',
                ],
                'Pie Cream Puff Cheese' => [
                    'desc' => 'Puff pie renyah bertabur gula dengan isian krim keju lembut gurih dingin yang lumer mewah di setiap gigitan.',
                    'note' => 'Susun rapi di dalam kotak box take-away agar kulit pie tidak remuk saat dibawa penumpang.',
                ],
                'Cookie Cream Puff Vanilla' => [
                    'desc' => 'Choux puff dengan lapisan biskuit cookie renyah manis di permukaannya, berisikan custard vanila lembut dingin berlimpah.',
                    'note' => 'Sajikan dingin, kemas dalam paper bag berlogo Beard Papa\'s dengan rapi.',
                ],
                'Cookie Cream Puff Chocolate' => [
                    'desc' => 'Kulit choux berkulit cookie cokelat garing dengan isian krim custard cokelat pekat yang lumer manis dan segar dingin di mulut.',
                    'note' => 'Berikan serbet kertas dan pastikan kue tidak tertindih barang belanjaan penumpang.',
                ],
                'Cookie Cream Puff Cheese' => [
                    'desc' => 'Perpaduan kulit kue choux bertabur remah biskuit keju renyah dengan isian krim keju custard lembut creamy dingin yang nagih.',
                    'note' => 'Jaga suhu tetap dingin sebelum diserahkan ke pelanggan take-away.',
                ],
            ],

            'MM JUICE RESTAURANT' => [
                'Paket Siomay A' => [
                    'desc' => 'Siomay ikan tenggiri asli kenyal gurih disajikan dengan tahu basah, kentang rebus, dan siraman saus bumbu kacang gurih manis kental.',
                    'note' => 'Bumbu kacang disiram atau dipisah sesuai request penumpang, berikan kecap manis dan jeruk limau.',
                ],
                'Paket Siomay B' => [
                    'desc' => 'Porsi komplit siomay tenggiri gurih kenyal berpadu telur rebus utuh, pare gurih, kubis rebus, dan saus kacang medok beraroma harum.',
                    'note' => 'Kemas rapi dalam paper bowl berventilasi, sertakan sendok garpu plastik higienis.',
                ],
                'Otak-Otak Bakar' => [
                    'desc' => 'Otak-otak daging ikan tenggiri asli berbalut daun pisang yang dibakar harum di atas arang, disajikan bersama saus kacang pedas manis asam segar.',
                    'note' => 'Bakar hingga daun pisang harum kecokelatan, bungkus otak-otak dalam kantong kertas kedap minyak.',
                ],
                'Pempek Lenjer' => [
                    'desc' => 'Pempek ikan tenggiri bentuk lonjong kenyal gurih yang digoreng garing keemasan, disajikan dengan irisan timun segar dan kuah cuko asam pedas manis kental.',
                    'note' => 'Kuah cuko WAJIB dikemas dalam botol/plastik klip tebal anti bocor agar tas kabin penumpang aman dari tumpahan.',
                ],
                'Pempek Kapal Selam' => [
                    'desc' => 'Pempek ikan tenggiri porsi besar dengan isian telur ayam utuh di dalamnya, digoreng garing dan disiram kuah cuko hitam manis pedas segar.',
                    'note' => 'Potong pempek kapal selam secara simetris, kemas cuko terpisah dan sertakan irisan timun.',
                ],
                'Mie Ayam' => [
                    'desc' => 'Mie kuning kenyal dengan topping potongan daging ayam bumbu gurih manis, sawi hijau segar, dan kuah kaldu gurih beraroma bawang putih.',
                    'note' => 'Pisahkan kuah kaldu dalam cup sup terpisah berpenutup rapat.',
                ],
                'Mie Siram Ayam' => [
                    'desc' => 'Mie kuning disiram kuah kental hangat berbumbu oriental gurih berisi potongan daging ayam, telur kocok, dan sayuran segar.',
                    'note' => 'Kemas mie dan kuah siram kental dengan tutup kencang bersegel tape anti tumpah.',
                ],
                'Mie Goreng Ayam' => [
                    'desc' => 'Mie goreng gurih manis beraroma wok-hei dengan suwiran ayam, bakso, telur orak-arik, dan sayuran kol serta sawi hijau renyah.',
                    'note' => 'Masak fresh saat dipesan, lengkapi dengan acar mentimun dan sambal sachet.',
                ],
                'Bakso Kuah' => [
                    'desc' => 'Semangkuk hangat bakso sapi kenyal berdaging padat dalam kuah kaldu sapi bening gurih harum taburan seledri dan bawang goreng.',
                    'note' => 'Kuah kaldu disajikan mendidih, wadah sup di-seal rapat dengan lid pelindung.',
                ],
                'Bihun Siram Ayam' => [
                    'desc' => 'Bihun beras lembut yang disiram kuah kental gurih beraroma kaldu ayam hangat, berisi potongan ayam empuk dan sayuran hijau.',
                    'note' => 'Kemas kuah kental rapat tanpa tumpah, sertakan sendok dan sumpit.',
                ],
                'Bihun Goreng Ayam' => [
                    'desc' => 'Bihun goreng kecap manis gurih berpadu suwiran ayam, irisan bakso, telur, dan sayuran kol sawi renyah.',
                    'note' => 'Pastikan bihun tidak terlalu berminyak, tata rapi di dalam lunch box takeaway.',
                ],
                'Kwetiau Siram Ayam' => [
                    'desc' => 'Kwetiau beras lebar kenyal disiram kuah kental hangat kaya rasa berisi potongan ayam empuk, bakso, dan sayuran segar beraroma minyak wijen.',
                    'note' => 'Tutup mangkuk kwetiau siram rapat dan pastikan tidak miring saat dimasukkan ke kantong take-away.',
                ],
                'Kwetiau Goreng Ayam' => [
                    'desc' => 'Kwetiau beras kenyal ditumis dengan bumbu oriental sedap, suwiran ayam, telur orak-arik, dan tauge renyah beraroma smoky.',
                    'note' => 'Sajikan hangat fresh dari wajan penggorengan.',
                ],
                'Kwetiau Goreng Seafood' => [
                    'desc' => 'Kwetiau beras lebar kenyal berpadu udang manis segar, cumi kenyal, bakso ikan, telur, dan tauge renyah dengan bumbu kecap gurih mantap.',
                    'note' => 'Pastikan udang dan cumi matang sempurna, sertakan acar mentimun dan cabai rawit potong.',
                ],
                'Nasi Goreng Cabe Hijau' => [
                    'desc' => 'Nasi goreng gurih pedas segar berbalut ulekan cabai hijau aromatik, suwiran ayam empuk, telur, dan taburan kerupuk renyah.',
                    'note' => 'Kemas dalam lunch box tebal ramah lingkungan, lengkapi dengan kerupuk renyah.',
                ],
                'Nasi Goreng Spesial' => [
                    'desc' => 'Nasi goreng oriental favorit bertabur potongan ayam, sosis, bakso sapi, dan telur ceplok mata sapi setengah matang gurih sedap.',
                    'note' => 'Telur ceplok diletakkan rapi di atas nasi, kemas kerupuk dan sambal terpisah.',
                ],
                'Nasi Goreng Seafood' => [
                    'desc' => 'Nasi goreng gurih berwangi sedap dengan potongan udang manis segar, cumi kenyal, bakso ikan, dan telur orak-arik lezat.',
                    'note' => 'Berikan potongan jeruk nipis di sudut box take-away.',
                ],
            ],

            'JAVA CAFFE' => [
                'Coffee of the Day' => [
                    'desc' => 'Seduhan kopi hitam segar harian dari biji kopi single origin pilihan nusantara dengan aroma kaya dan karakter rasa seimbang.',
                    'note' => 'Tuang seduhan kopi fresh dari termo brewer, gunakan cup panas tahan insulasi.',
                ],
                'Café Au Lait' => [
                    'desc' => 'Kopi seduh hitam khas Prancis yang dipadukan seimbang dengan susu steamed panas lembut berbusa halus menenangkan.',
                    'note' => 'Perbandingan 1:1 antara kopi dan susu panas, pasang penutup lid bertutup klip.',
                ],
                'Tuxedo Mocha' => [
                    'desc' => 'Perpaduan mewah antara double shot espresso dengan saus cokelat hitam pekat (dark choco) dan cokelat putih manis lembut creamy.',
                    'note' => 'Aduk kedua saus cokelat hingga larut sebelum dituangkan steamed milk dan espresso.',
                ],
                'White Chocolate Mocha' => [
                    'desc' => 'Kopi espresso mantap berpadu lezatnya sirup cokelat putih manis creamy dan steamed milk bertabur whipped cream tebal.',
                    'note' => 'Berikan semprotan whipped cream lembut di atas minuman, tutup dengan dome lid.',
                ],
                'Café Mocha' => [
                    'desc' => 'Kopi espresso berpadu susu panas dan bubuk cokelat kakao pekat manis gurih yang menghangatkan tubuh di bandara.',
                    'note' => 'Aduk rata saus cokelat dengan espresso sebelum dituangkan susu berbusa.',
                ],
                'Cinnamon Roll Latte' => [
                    'desc' => 'Minuman kopi latte istimewa dengan aroma kayu manis manis gurih dan sirup brown sugar mentega khas kue cinnamon roll hangat.',
                    'note' => 'Taburkan bubuk kayu manis asli di atas foam susu latte.',
                ],
                'Café Latte' => [
                    'desc' => 'Kopi espresso klasik berpadu susu segar steamed bersuhu pas dengan tekstur velvety halus dan rasa kopi bersahabat.',
                    'note' => 'Sajikan dalam suhu 65°C, gunakan cup take-away insulasi ganda anti-panas.',
                ],
                'Caramel Macchiato' => [
                    'desc' => 'Susu vanila steamed lembut bertingkat dengan siraman double shot espresso bold di atasnya dan pola saus karamel lezat legit.',
                    'note' => 'Buat pola silang saus karamel di atas lapisan busa susu sebelum ditutup lid.',
                ],
                'Ultimate Vanilla Latte' => [
                    'desc' => 'Kopi latte lembut beraroma harum vanila Madagaskar yang manis menenangkan, berpadu seimbang dengan espresso pilihan.',
                    'note' => 'Pastikan sirup vanila tercampur rata, pasang sleeve karton anti panas.',
                ],
                'Cappuccino' => [
                    'desc' => 'Kopi klasik Italia dengan komposisi seimbang espresso mantap, susu hangat, dan lapisan busa tebal bertabur cokelat.',
                    'note' => 'Taburi cokelat bubuk tipis di atas lapisan microfoam sebelum cup ditutup.',
                ],
                'Americano' => [
                    'desc' => 'Ekstrak espresso pekat yang dilarutkan air panas murni, menghadirkan cita rasa kopi murni yang bersih, tegas, dan menyegarkan.',
                    'note' => 'Berikan pilihan gula cair atau gula sachet saat penyerahan pesanan.',
                ],
            ],

            'SOLARIA' => [
                'Nasi + Ayam Goreng Mentega' => [
                    'desc' => 'Nasi putih hangat dengan potongan ayam goreng empuk berlumur saus mentega manis gurih karamel beraroma bawang bombay sedap.',
                    'note' => 'Pastikan saus mentega melumuri ayam merata, tutup box makan rapat dan sertakan sendok garpu.',
                ],
                'Nasi Bistik Ayam' => [
                    'desc' => 'Nasi putih pulen disajikan dengan fillet ayam goreng tepung renyah berbalut saus bistik gurih manis kaya rempah dan sayuran kentang wortel.',
                    'note' => 'Siramkan saus bistik panas di atas ayam krispi, kemas dalam bento box tebal.',
                ],
                'Nasi Cap Cay' => [
                    'desc' => 'Nasi pulen hangat dengan aneka tumisan sayuran segar brokoli, kembang kol, wortel, jamur, dan bakso dalam kuah kental gurih beraroma sedap.',
                    'note' => 'Kemas capcay dalam paper bowl tahan panas agar kuah kental tidak tumpah ke tas kabin penumpang.',
                ],
                'Nasi Ayam Cah Jamur' => [
                    'desc' => 'Nasi hangat dengan tumisan potongan daging ayam empuk, irisan jamur kancing kenyal, dan sayuran berkuah kecap gurih manis khas Solaria.',
                    'note' => 'Masak fresh saat dipesan, pastikan kuah tumis kental dan tidak bocor.',
                ],
                'Nasi Fu Yung Hai' => [
                    'desc' => 'Nasi putih dengan dadar telur tebal goreng garing berisi daging ayam dan kepiting, disiram saus asam manis segar berpadu kacang polong manis.',
                    'note' => 'Saus asam manis dipisahkan dalam cup terpisah agar fu yung hai tetap renyah garing saat dinikmati.',
                ],
                'Kwetiau Goreng Ayam' => [
                    'desc' => 'Kwetiau beras kenyal ditumis dengan suwiran ayam, bakso, telur orak-arik, dan sayuran beraroma gurih smoky khas wajan oriental.',
                    'note' => 'Goreng dengan api besar agar aroma wok-hei maksimal, lengkapi dengan acar dan kerupuk.',
                ],
                'Kwetiau Seafood Goreng' => [
                    'desc' => 'Kwetiau beras lebar kenyal berpadu udang manis segar, cumi kenyal, bakso ikan, telur, dan tauge renyah dengan bumbu kecap gurih mantap.',
                    'note' => 'Pastikan seafood matang sempurna dan segar, sertakan sambal rawit sachet.',
                ],
                'Kwetiau Ayam' => [
                    'desc' => 'Kwetiau kuah bening hangat gurih dengan topping daging ayam cincang manis gurih, sawi hijau segar, dan taburan bawang putih goreng.',
                    'note' => 'Kuah kaldu ayam WAJIB dipisah dalam mangkuk cup berpenutup kencang.',
                ],
                'Bihun Goreng Ayam' => [
                    'desc' => 'Bihun beras lembut ditumis bumbu kecap manis gurih berpadu suwiran ayam, irisan bakso, telur, dan sayuran kol sawi renyah.',
                    'note' => 'Kemas rapi dalam box makanan ramah lingkungan.',
                ],
                'Bihun Goreng Seafood' => [
                    'desc' => 'Bihun goreng beraroma harum laut dengan topping udang segar, cumi kenyal, bakso ikan, dan telur orak-arik gurih sedap.',
                    'note' => 'Tata seafood di bagian atas bihun agar tampilan memikat.',
                ],
                'Fu Mie' => [
                    'desc' => 'Mie kering renyah keemasan yang disiram kuah kental hangat berisi potongan ayam, telur, jamur, dan sayuran manis gurih.',
                    'note' => 'Mie renyah WAJIB dibungkus terpisah dari kuah siram kental agar mie tidak letoy di jalan.',
                ],
                'Fu Mie Seafood' => [
                    'desc' => 'Mie garing renyah berpadu siraman kuah kental gurih aneka seafood udang, cumi, bakso ikan, dan sayuran segar beraroma sedap.',
                    'note' => 'Pisahkan mie garing dalam kantong plastik kering, kuah seafood dalam wadah cup tertutup rapat.',
                ],
                'Lo Mie' => [
                    'desc' => 'Mie kuning lembut kenyal dalam kuah kental kecap hitam gurih manis beraroma kaldu udang dan bawang putih, berisi daging ayam dan sayuran.',
                    'note' => 'Kemas dalam mangkuk bowl tahan panas ber-seal tape pelindung.',
                ],
                'Cap Cay Seafood' => [
                    'desc' => 'Tumisan sayuran hijau komplit dengan potongan udang segar, cumi kenyal, bakso ikan, dan wortel manis dalam saus tiram gurih kental.',
                    'note' => 'Pastikan tutup mangkuk tertutup rapat dan masukkan dalam kantong take-away tegak lurus.',
                ],
                'Nasi + Cumi Goreng Mentega' => [
                    'desc' => 'Nasi pulen hangat dengan potongan cumi goreng empuk kenyal berbalut saus mentega kecap manis gurih beraroma bawang bombay harum.',
                    'note' => 'Masak cumi dengan cepat agar teksturnya tetap empuk lembut dan tidak alot.',
                ],
            ],

            'CAFE GARDEN' => [
                'Beef Yakiniku Rice Bowl' => [
                    'desc' => 'Nasi hangat dengan irisan daging sapi empuk saus yakiniku manis gurih beraroma wijen sangrai wangi dan sayuran tumis.',
                    'note' => 'Kemas dalam paper bowl bento, taburi wijen sangrai di atas daging sapi.',
                ],
                'Chicken Katsu Mayo Rice Bowl' => [
                    'desc' => 'Nasi putih dengan fillet ayam katsu goreng renyah saus mayones creamy gurih dan taburan nori garing.',
                    'note' => 'Katsu dipotong simetris, mayones disiram rapi di atas katsu krispi.',
                ],
                'Spicy Chicken Wings Rice Bowl' => [
                    'desc' => 'Nasi putih hangat dengan sayap ayam goreng saus pedas manis beraroma rempah dan taburan wijen.',
                    'note' => 'Sajikan panas, sediakan tisu makan ekstra dan kantong anti-minyak.',
                ],
                'Chicken Satay Ready Meal' => [
                    'desc' => 'Paket praktis sate ayam panggang bumbu kacang gurih manis kental dengan lontong empuk siap santap di bandara.',
                    'note' => 'Bumbu kacang dan lontong dikemas dalam kompartemen bersegel rapat.',
                ],
                'Chicken Geprek' => [
                    'desc' => 'Nasi pulen dengan ayam goreng tepung renyah yang digeprek dengan sambal bawang cabai rawit pedas meledak.',
                    'note' => 'Kemas sambal bawang terpisah jika penumpang meminta tingkat pedas terpisah.',
                ],
                'Spaghetti Carbonara' => [
                    'desc' => 'Pasta spaghetti kenyal lembut berbalut saus krim carbonara gurih, irisan smoked beef, dan taburan keju parmesan.',
                    'note' => 'Sajikan panas dalam mangkuk bowl tahan panas, taburi parsley kering.',
                ],
                'Beef Blackpaper' => [
                    'desc' => 'Nasi putih dengan potongan daging sapi empuk berbalut saus lada hitam pedas manis gurih dan paprika renyah.',
                    'note' => 'Pastikan saus lada hitam kental dan tidak bocor ke luar wadah.',
                ],
                'Kopi Nusantara' => [
                    'desc' => 'Racikan kopi susu khas nusantara dengan espresso mantap, susu kental manis gurih, dan gula aren legit harum.',
                    'note' => 'Aduk rata sebelum disajikan, gunakan lid penutup anti tumpah.',
                ],
                'Caramel / Hazelnut / Vanilla Latte' => [
                    'desc' => 'Kopi latte dingin segar dengan pilihan sirup rasa karamel legit, hazelnut gurih, atau vanila manis lembut.',
                    'note' => 'Tutup cup dengan mesin cup sealer atau lid dome anti-bocor.',
                ],
                'Mocha Latte' => [
                    'desc' => 'Perpaduan kopi espresso, susu segar dingin, dan cokelat pekat gurih yang manis menyegarkan.',
                    'note' => 'Kocok larutan cokelat dan espresso merata dengan susu dingin.',
                ],
                'Lychee Mojito' => [
                    'desc' => 'Mocktail dingin menyegarkan dari soda bersari buah leci manis asam, perasan jeruk nipis segar, dan daun mint dingin.',
                    'note' => 'Masukkan 2 butir buah leci utuh dan irisan daun mint, seal cup rapat.',
                ],
                'Strawberry Milkshake' => [
                    'desc' => 'Minuman susu kocok dingin lembut rasa stroberi manis segar dengan topping whipped cream tebal.',
                    'note' => 'Semprotkan whipped cream di atas milkshake, sediakan sedotan ukuran besar.',
                ],
                'Mix Platter' => [
                    'desc' => 'Camilan komplit sosis sapi goreng, kentang goreng renyah, dan nugget ayam dengan cocolan saus sambal dan tomat.',
                    'note' => 'Tiriskan minyak dengan kertas serap, sertakan saus sachet.',
                ],
                'Chicken Bites & Fries' => [
                    'desc' => 'Potongan daging ayam renyah garing berbumbu gurih berpadu kentang goreng krispi keemasan.',
                    'note' => 'Kemas dalam box snack berventilasi udara agar tetap renyah.',
                ],
                'Batagor' => [
                    'desc' => 'Olahan baso tahu goreng ikan tenggiri renyah berpadu siraman bumbu kacang gurih manis dan kecap manis.',
                    'note' => 'Potong batagor rapi, kemas bumbu kacang terpisah.',
                ],
            ],

            'ROTI BOY' => [
                'Rotiboy' => [
                    'desc' => 'Roti bun hangat berlapis krim kopi moka renyah garing di luar dengan isian mentega gurih meleleh di dalam.',
                    'note' => 'Sajikan fresh dari warmer box, kemas satuan dalam kantong kertas anti-minyak berlabel Rotiboy.',
                ],
                'Rotigal' => [
                    'desc' => 'Roti bun manis bertabur remah biskuit vanila renyah keemasan dengan isian krim keju mentega gurih lumer.',
                    'note' => 'Pastikan kemasan tidak tertindih roti hangat lainnya.',
                ],
                'Chocoboy' => [
                    'desc' => 'Roti bun lembut berbalut kerak cokelat renyah manis wangi dengan isian lelehan cokelat premium di dalamnya.',
                    'note' => 'Hangatkan 20 detik jika diminta hangat oleh penumpang.',
                ],
                'Rotiboy x Kopiko' => [
                    'desc' => 'Roti bun kolaborasi spesial beraroma permen kopi Kopiko legendaris dengan renyahnya kerak kopi dan mentega lumer.',
                    'note' => 'Sajikan hangat untuk sensasi kopi Kopiko yang harum semerbak.',
                ],
                'Krispy Stix (Chocolate / Cheese / Garlic)' => [
                    'desc' => 'Stik pastry renyah garing beraroma butter dengan pilihan taburan cokelat manis, keju gurih, atau bawang putih aromatik.',
                    'note' => 'Kemas dalam kotak tabung karton agar stik tidak patah saat dibawa.',
                ],
                'Air Mineral' => [
                    'desc' => 'Air mineral pegunungan murni kemasan botol higienis pelepas dahaga perjalanan.',
                    'note' => 'Sajikan dingin atau suhu ruang sesuai permintaan penumpang.',
                ],
                'TBK 250 ml' => [
                    'desc' => 'Teh botol kemasan praktis rasa melati manis segar yang menyejukkan tenggorokan.',
                    'note' => 'Sajikan dingin, sertakan sedotan.',
                ],
                'Fruit Tea Pouch' => [
                    'desc' => 'Minuman teh rasa buah segar kemasan pouch praktis siap minum di gate pesawat.',
                    'note' => 'Pastikan kemasan pouch utuh dan dingin.',
                ],
                'Minuman Botol' => [
                    'desc' => 'Minuman teh/jus kemasan botol dingin menyegarkan untuk bekal terbang.',
                    'note' => 'Sajikan dingin langsung dari showcase.',
                ],
            ],

            'LA CAFE' => [
                'Rawon Empal' => [
                    'desc' => 'Nasi rawon kuah hitam kluwek kaya rempah dengan empal sapi manis gurih empuk, tauge pendek, dan sambal terasi.',
                    'note' => 'Kuah rawon dikemas dalam cup sup ber-seal anti bocor. Tauge dan sambal dipisah.',
                ],
                'Nasi Penyetan Spesial' => [
                    'desc' => 'Nasi hangat dengan kombinasi ayam goreng, tempe, tahu, telur, dan ulekan sambal terasi pedas gurih mantap.',
                    'note' => 'Sambal terasi ditaruh di wadah tertutup agar tidak membasahi nasi.',
                ],
                'Lontong Kikil' => [
                    'desc' => 'Lontong empuk dalam siraman kuah kikil sapi gurih berkaldu kaya rempah serai dan daun jeruk khas Jawa Timur.',
                    'note' => 'Kemas kuah kikil rapat tanpa tumpah, sertakan potongan jeruk nipis dan sambal.',
                ],
                'Nasi Krengsengan Daging' => [
                    'desc' => 'Nasi pulen dengan potongan daging sapi empuk bumbu petis kecap manis pedas gurih beraroma bawang goreng.',
                    'note' => 'Kemas dalam lunch box bersekat rapi.',
                ],
                'Nasi Rendang Daging' => [
                    'desc' => 'Nasi hangat disajikan dengan sepotong rendang daging sapi empuk berbalut bumbu rempah pekat harum.',
                    'note' => 'Lumuri bumbu rendang tebal di atas daging sapi.',
                ],
                'Nasi Ayam Goreng Kalasan' => [
                    'desc' => 'Nasi putih dengan ayam goreng ungkep bumbu manis rempah Kalasan yang gurih meresap dengan sambal ulek.',
                    'note' => 'Ayam disajikan hangat dengan kremesan garing.',
                ],
                'Nasi Ayam Kare' => [
                    'desc' => 'Nasi hangat berpadu kuah kari ayam santan kuning kental gurih berempah dengan potongan ayam empuk dan kentang.',
                    'note' => 'Kuah kare dikemas dalam mangkuk anti bocor.',
                ],
                'Soto Ayam' => [
                    'desc' => 'Soto ayam kuah kuning bening gurih harum beraroma koya, suwiran ayam melimpah, dan soun lembut.',
                    'note' => 'Kuah soto dan soun dipisahkan agar tidak mengembang.',
                ],
                'Iced Chocolatine Coffee' => [
                    'desc' => 'Minuman es kopi susu cokelat creamy beraroma pastry mentega dan cokelat manis lezat.',
                    'note' => 'Tutup cup dengan seal anti bocor.',
                ],
                'Iced Kopi Gula Aren' => [
                    'desc' => 'Kopi espresso robusta segar berpadu susu dingin gurih dan manis alami gula aren murni.',
                    'note' => 'Pasang lid press anti tumpah.',
                ],
                'Iced Latte' => [
                    'desc' => 'Kopi espresso klasik berpadu susu segar dingin creamy pelepas dahaga sebelum terbang.',
                    'note' => 'Sajikan dingin dengan es batu bersih.',
                ],
                'Iced Strawberry Latte' => [
                    'desc' => 'Susu segar rasa stroberi manis asam segar dipadu shot espresso yang lembut seimbang.',
                    'note' => 'Buat gradasi warna stroberi dan kopi yang cantik sebelum di-seal.',
                ],
                'Hot Kopi Gula Aren' => [
                    'desc' => 'Kopi susu panas hangat menenangkan dengan manis legit gula aren asli.',
                    'note' => 'Gunakan cup panas bertutup klip.',
                ],
                'Hot Cappuccino' => [
                    'desc' => 'Kopi espresso hangat mantap dengan microfoam tebal bertekstur sutra bertabur bubuk cokelat.',
                    'note' => 'Taburi bubuk cokelat di atas busa susu.',
                ],
            ],

            'SHIHLIN' => [
                'XXL Crispy Chicken Original' => [
                    'desc' => 'Fillet dada ayam tanpa tulang renyah ekstra besar bertabur bumbu rempah gurih lada garam khas Taiwan.',
                    'note' => 'Potong ayam menyerong dengan gunting khusus, masukkan ke paper bag Shihlin berventilasi.',
                ],
                'XXL Crispy Chicken Barbeque' => [
                    'desc' => 'Ayam fillet renyah jumbo bertabur bumbu barbekyu manis gurih smoky yang nagih di lidah.',
                    'note' => 'Kocok bumbu BBQ merata di kantong shaker sebelum diserahkan.',
                ],
                'XXL Crispy Chicken Seaweed' => [
                    'desc' => 'Ayam krispi raksasa renyah keemasan bertabur bumbu rumput laut nori asin gurih aromatik.',
                    'note' => 'Taburkan bumbu rumput laut merata di kedua sisi ayam.',
                ],
                'Happy! RiceBox Original' => [
                    'desc' => 'Nasi hangat dengan potongan XXL Crispy Chicken bumbu original, tahu gurih, dan taburan nori.',
                    'note' => 'Kemas dalam box take-away bersekat, sertakan sendok garpu.',
                ],
                'Happy! RiceBox Barbeque' => [
                    'desc' => 'Nasi pulen bento dengan ayam krispi saus BBQ manis gurih, telur orak-arik, dan bumbu khas.',
                    'note' => 'Ayam krispi ditata di atas nasi hangat.',
                ],
                'Happy! RiceBox Seaweed' => [
                    'desc' => 'Nasi hangat bertabur potongan ayam krispi bumbu rumput laut gurih renyah dan telur.',
                    'note' => 'Tutup box rapat dan lapisi kantong bersih.',
                ],
                'Tempura Rice Box' => [
                    'desc' => 'Nasi putih pulen dengan potongan seafood tempura kenyal renyah berbumbu sedap.',
                    'note' => 'Sajikan hangat, taburi daun bawang iris.',
                ],
                'Handmade Oyster Mee Sua' => [
                    'desc' => 'Mie sua lembut sutra berkuah kental gurih kaldu tiram hangat dengan potongan tiram manis dan suwiran ayam.',
                    'note' => 'Kemas dalam mangkuk sup tahan panas bersegel tape anti tumpah.',
                ],
                'Handmade Tempura Mee Sua' => [
                    'desc' => 'Mie sua kuah kental kaldu gurih disajikan dengan potongan tempura ikan goreng kenyal renyah.',
                    'note' => 'Tempura diletakkan di bagian atas kuah sesaat sebelum di-seal.',
                ],
                'Crispy Floss Egg Crepe' => [
                    'desc' => 'Crepe telur lembut khas Taiwan dengan gulungan abon sapi manis gurih dan saus spesial.',
                    'note' => 'Potong crepe menjadi 6 bagian rapi dalam box snack.',
                ],
                'Sausage Cheese Egg Crepe' => [
                    'desc' => 'Crepe telur lembut berisikan sosis sapi panggang dan lelehan keju mozarella gurih mulur.',
                    'note' => 'Sajikan hangat agar keju tetap meleleh sedap.',
                ],
                'Mushroom Cheese Egg Crepe' => [
                    'desc' => 'Crepe telur hangat berisikan jamur tumis gurih berpadu lelehan keju cheddar lembut.',
                    'note' => 'Bungkus rapi dalam kotak kue bersegel.',
                ],
                'Crispy Salt Pepper Mushroom' => [
                    'desc' => 'Jamur tiram goreng tepung renyah garing bertabur bumbu lada garam gurih khas Shihlin.',
                    'note' => 'Tiriskan minyak dengan kertas penyerap sebelum dimasukkan ke kantong kertas.',
                ],
                'Sweet Plum Potato Fries' => [
                    'desc' => 'Ubi manis goreng garing renyah keemasan bertabur bubuk plum manis asam segar yang otentik.',
                    'note' => 'Taburi bubuk plum merata saat ubi masih panas.',
                ],
                'Seafood Tempura' => [
                    'desc' => 'Olahan seafood olahan khas Taiwan goreng kenyal gurih bertabur bumbu lada garam sedap.',
                    'note' => 'Potong ukuran bite-sized dan kemas rapi.',
                ],
            ],

            'BANGI CAFE' => [
                'Nasi Ayam Geprek Sambel Bawang' => [
                    'desc' => 'Nasi hangat dengan ayam goreng krispi digeprek sambal bawang mentah pedas gurih berminyak.',
                    'note' => 'Ayam digeprek fresh, sambal bawang ditaruh di wadah terpisah jika diminta tidak terlalu pedas.',
                ],
                'Malaysian Grilled Chicken + Hainan Rice' => [
                    'desc' => 'Nasi hainan harum berpadu ayam panggang bumbu rempah khas Melayu yang juicy manis gurih.',
                    'note' => 'Sajikan dengan saus jahe dan cabai hainan dalam cup saus terpisah.',
                ],
                'Malaysian Salted Chicken + Hainan Rice' => [
                    'desc' => 'Nasi hainan gurih berpadu ayam kukus garam laut khas Malaysia yang lembut gurih alami.',
                    'note' => 'Kemas dalam bento box bersekat, berikan kuah kaldu bening terpisah.',
                ],
                'Sop Buntut Bakar + Nasi' => [
                    'desc' => 'Nasi putih dengan buntut sapi bakar manis karamel empuk beraroma arang, disajikan bersama kuah sup bening gurih.',
                    'note' => 'Buntut bakar dibungkus daun pisang, kuah sup dikemas dalam mangkuk berpenutup rapat.',
                ],
                'Sop Buntut + Nasi' => [
                    'desc' => 'Sup buntut sapi empuk dalam kuah kaldu kaya rempah kapulaga pala hangat, lengkap dengan nasi pulen.',
                    'note' => 'Gunakan cup sup double-wall anti panas dan bocor.',
                ],
                'Rawon Sapi Spesial + Nasi' => [
                    'desc' => 'Nasi rawon daging sapi kuah kluwek hitam pekat dengan sambal terasi dan tauge pendek segar.',
                    'note' => 'Kemas kuah rawon rapat tanpa rembes ke luar.',
                ],
                'Rice Bowl Blackpepper Beef' => [
                    'desc' => 'Mangkuk nasi hangat dengan irisan daging sapi tumis saus lada hitam gurih pekat beraroma bawang bombay.',
                    'note' => 'Daging sapi lada hitam ditata di atas nasi pulen.',
                ],
                'Rice Bowl Teriyaki Beef' => [
                    'desc' => 'Nasi putih dengan irisan daging sapi berbalut saus teriyaki manis gurih mengkilap dan wijen sangrai.',
                    'note' => 'Saus teriyaki dituangkan merata di atas daging.',
                ],
                'Nasi Goreng Seafood' => [
                    'desc' => 'Nasi goreng oriental dengan udang manis, cumi kenyal, dan telur orak-arik beraroma smoky.',
                    'note' => 'Sajikan hangat dengan kerupuk dan acar.',
                ],
                'Kwetiau Goreng Seafood' => [
                    'desc' => 'Kwetiau beras kenyal berpadu seafood segar dan tauge renyah dalam bumbu kecap gurih mantap.',
                    'note' => 'Masak dengan api besar agar aroma harum keluar.',
                ],
                'Nasi Ayam Geprek Sambel Matah' => [
                    'desc' => 'Nasi hangat dengan ayam krispi berlumur sambal matah serai dan bawang merah segar aromatik.',
                    'note' => 'Sambal matah disiram sesaat sebelum diserahkan agar tetap segar.',
                ],
                'Nasi Cumi Saos Telur Asin' => [
                    'desc' => 'Nasi putih dengan cumi goreng krispi berbalut saus kuning telur asin (salted egg) gurih creamy legit.',
                    'note' => 'Pastikan saus telur asin melapisi seluruh potongan cumi.',
                ],
                'Malacca Toast' => [
                    'desc' => 'Roti panggang renyah tebal dengan selai srikaya Malaka manis wangi dan irisan mentega dingin meleleh.',
                    'note' => 'Potong simetris, bungkus dengan kertas roti pembungkus panas.',
                ],
                'Nougat Meisses Toast' => [
                    'desc' => 'Roti bakar renyah dengan selai kacang nougat manis gurih bertabur butiran cokelat meises melimpah.',
                    'note' => 'Panggang hingga keemasan, olesi selai saat roti masih hangat.',
                ],
                'Pisang Goreng Keju Susu' => [
                    'desc' => 'Pisang kepok goreng renyah manis bertabur parutan keju cheddar gurih dan siraman susu kental manis.',
                    'note' => 'Tiriskan minyak pisang sebelum ditaburi keju dan susu.',
                ],
            ],

            'FAMI CAFE' => [
                'Kopi Susu Keluarga (R)' => [
                    'desc' => 'Kopi susu andalan Fami Cafe perpaduan espresso mantap, susu segar gurih, dan gula aren legit harum.',
                    'note' => 'Seal cup menggunakan mesin sealer otomatis, sediakan sedotan.',
                ],
                'Caramel Macchiato (R)' => [
                    'desc' => 'Susu segar dingin lembut berpadu shot espresso pekat dan siraman saus karamel manis wangi.',
                    'note' => 'Tuang saus karamel di atas lapisan foam susu.',
                ],
                'Black Sea Salt Latte (R)' => [
                    'desc' => 'Kopi latte unik dengan krim busa garam laut (sea salt foam) gurih lembut yang memperkaya rasa kopi.',
                    'note' => 'Tuang sea salt foam perlahan di lapisan paling atas.',
                ],
                'Butterscotch Sea Salt Latte (R)' => [
                    'desc' => 'Paduan sirup mentega butterscotch manis karamel dengan sea salt foam gurih dan espresso mantap.',
                    'note' => 'Pastikan takaran sirup butterscotch seimbang dengan espresso.',
                ],
                'Mochapresso' => [
                    'desc' => 'Minuman perpaduan espresso pekat dan cokelat kakao nikmat bertekstur kental manis gurih.',
                    'note' => 'Aduk rata cokelat dan espresso sebelum diberi es dan susu.',
                ],
                'Matcha Latte (R)' => [
                    'desc' => 'Susu segar dingin dipadu bubuk matcha Jepang berkualitas dengan rasa manis creamy teh hijau yang menenangkan.',
                    'note' => 'Whisk matcha dengan air hangat hingga larut sebelum dituangi susu.',
                ],
                'Premium Chocolate (R)' => [
                    'desc' => 'Minuman cokelat dingin kaya rasa dari biji kakao premium dengan rasa manis creamy yang memanjakan lidah.',
                    'note' => 'Kocok rata dengan shaker es sebelum dituang ke cup.',
                ],
                'Bento Set A' => [
                    'desc' => 'Paket bento nasi hangat dengan ayam goreng renyah saus teriyaki, salad segar, dan telur tamagoyaki.',
                    'note' => 'Kemas dalam box bento sekat 3, sertakan sumpit dan sendok.',
                ],
                'Bento Set C' => [
                    'desc' => 'Paket bento nasi putih dengan katsu ayam renyah keemasan, saus tonkatsu gurih, dan sayuran segar.',
                    'note' => 'Katsu dipotong rapi, saus tonkatsu disiram di atasnya.',
                ],
                'Combo Value A' => [
                    'desc' => 'Paket hemat nasi hangat dengan sepotong ayam goreng krispi dan minuman segar teh dingin.',
                    'note' => 'Kemas ayam dalam kantong kertas anti minyak.',
                ],
                'Combo Super A' => [
                    'desc' => 'Paket puas nasi pulen dengan 2 potong ayam goreng krispi gurih dan segelas minuman dingin.',
                    'note' => 'Sajikan ayam hangat, tutup cup minuman rapat.',
                ],
                'Combo Super B' => [
                    'desc' => 'Paket ayam goreng krispi berpadu burger mini dan minuman soda segar pelepas dahaga.',
                    'note' => 'Bungkus burger rapi dengan kertas pembungkus burger.',
                ],
                'Combo Super Duo' => [
                    'desc' => 'Paket porsi berdua berisi 2 nasi, 4 potong ayam krispi bumbu renyah, dan 2 minuman dingin.',
                    'note' => 'Tata seluruh paket dalam kantong take-away besar yang kokoh.',
                ],
                'Crispy Chicken 4pcs' => [
                    'desc' => 'Empat potong ayam goreng krispi berbumbu rempah gurih renyah keemasan yang juicy di dalam.',
                    'note' => 'Kemas dalam bucket atau box ayam berventilasi udara.',
                ],
            ],

            'DOUGH LAB' => [
                'The OG' => [
                    'desc' => 'Cookie ikonik Dough Lab bertekstur chewy lembut dengan lelehan chocolate chips premium yang lumer manis gurih.',
                    'note' => 'Kemas satuan dalam kantong kaca transparan higienis Dough Lab.',
                ],
                'The Diva' => [
                    'desc' => 'Cookie red velvet lembut dengan potongan cokelat putih manis creamy yang meleleh di setiap gigitan.',
                    'note' => 'Pastikan cookie tidak tertindih agar tekstur lembut tidak hancur.',
                ],
                'OG Extra Dirty' => [
                    'desc' => 'Varian OG cookie berpadu lumuran saus cokelat hitam pekat ekstra yang kaya rasa bagi pencinta cokelat sejati.',
                    'note' => 'Beri alas kertas kue anti-lengket di dalam box take-away.',
                ],
                'Dark Chocolate' => [
                    'desc' => 'Cookie renyah lembut dari bubuk kakao hitam pekat bertabur dark chocolate chunks manis legit seimbang.',
                    'note' => 'Simpan di showcase kering, kemas rapi.',
                ],
                'Red Velvet' => [
                    'desc' => 'Cookie warna merah velvet lembut dengan kombinasi vanila dan cokelat putih yang manis lumer memikat.',
                    'note' => 'Handle dengan sarung tangan makanan bersih.',
                ],
                'The Choco Chip' => [
                    'desc' => 'Cookie klasik renyah di tepi dan chewy di tengah dengan taburan butiran cokelat chip melimpah.',
                    'note' => 'Sajikan fresh, kemas dalam kantong cookie tahan minyak.',
                ],
                'Matcha Cream Cheese' => [
                    'desc' => 'Cookie teh hijau matcha Jepang aromatik dengan isian keju krim (cream cheese) gurih lembut di tengahnya.',
                    'note' => 'Pastikan isian cream cheese di tengah tetap utuh dan dingin.',
                ],
                'Macadamia Cookie' => [
                    'desc' => 'Cookie mentega wangi bertabur kacang macadamia panggang renyah gurih dan cokelat putih manis.',
                    'note' => 'Kemas dalam box cookie khusus bersegel pita.',
                ],
                'Sea Salt Cookie' => [
                    'desc' => 'Cookie cokelat manis gurih dengan taburan butiran garam laut murni (sea salt flakes) yang mempertegas kelezatannya.',
                    'note' => 'Pastikan butiran sea salt tidak rontok saat dimasukkan ke kantong.',
                ],
                'Biscoff Cookie' => [
                    'desc' => 'Cookie mentega gurih dengan isian selai karamel Lotus Biscoff dan taburan biskuit renyah beraroma kayu manis.',
                    'note' => 'Bungkus satuan dalam kantong roti wax paper.',
                ],
                'Peanut Butter Cookie' => [
                    'desc' => 'Cookie gurih manis bertabur selai kacang tanah panggang yang creamy dan legit di lidah.',
                    'note' => 'Pastikan kemasan higienis dan rapat.',
                ],
                'Double Chocolate Cookie' => [
                    'desc' => 'Sensasi cokelat ganda dalam adonan kue cokelat hitam pekat bertabur potongan cokelat meleleh.',
                    'note' => 'Hangatkan 15 detik jika pelanggan ingin menikmati cokelat meleleh.',
                ],
                'Cookies & Cream' => [
                    'desc' => 'Cookie renyah berpadu remahan biskuit oreo hitam pekat dan krim vanila manis lembut.',
                    'note' => 'Susun rapi di dalam kotak box take-away.',
                ],
                'Nutella Cookie' => [
                    'desc' => 'Cookie hangat renyah berisikan lelehan selai cokelat hazelnut Nutella manis gurih yang melimpah.',
                    'note' => 'Pastikan lelehan Nutella tidak meluber ke luar kemasan.',
                ],
            ],

            'YOGURT REPUBLIC' => [
                'Summer Strawberry' => [
                    'desc' => 'Frozen yogurt segar dingin rasa buah stroberi alami dengan rasa asam manis menyegarkan dan rendah kalori.',
                    'note' => 'Tuang froyo dari mesin dispenser, pasang tutup dome rapat dan beri sendok plastik.',
                ],
                'Pina Colada' => [
                    'desc' => 'Froyo tropis perpaduan rasa nanas manis asam segar dan kelapa gurih yang menyegarkan di suasana bandara.',
                    'note' => 'Pastikan cup bersih dan tidak ada lelehan di sisi luar cup.',
                ],
                'Verry Berry' => [
                    'desc' => 'Frozen yogurt dingin bertabur aneka sari buah berry kaya antioksidan dengan rasa asam manis alami.',
                    'note' => 'Beri topping buah berry segar di atas froyo.',
                ],
                'Go Bananas' => [
                    'desc' => 'Froyo lembut berpadu rasa pisang tropis manis creamy yang lembut memanjakan lidah.',
                    'note' => 'Sajikan beku pas agar tidak cepat meleleh.',
                ],
                'Mango Passion' => [
                    'desc' => 'Perpaduan mangga tropis harum manis dan markisa asam segar dalam frozen yogurt beku yang menyegarkan.',
                    'note' => 'Tutup dengan dome lid rapat.',
                ],
                'Green Smoothie' => [
                    'desc' => 'Jus smoothie sehat dari bayam hijau segar, apel manis, dan perasan jeruk lemon yang kaya vitamin.',
                    'note' => 'Blender hingga halus tanpa serat kasar, seal cup rapat.',
                ],
                'Froyopops Small' => [
                    'desc' => 'Es stik yogurt beku buah ukuran personal yang praktis dinikmati sambil berjalan menuju boarding gate.',
                    'note' => 'Ambil dari freezer showcase, kemas dengan kantong es pelindung dingin.',
                ],
                'Froyopops Large' => [
                    'desc' => 'Es stik froyo buah segar ukuran besar yang dingin segar dan menyehatkan tubuh.',
                    'note' => 'Pastikan es stik tetap beku saat diserahkan ke penumpang.',
                ],
                'Watermelon Mint Juice' => [
                    'desc' => 'Jus semangka murni manis segar dipadu sensasi dingin daun mint aromatik pelepas dahaga.',
                    'note' => 'Sajikan dingin tanpa tambahan gula berlebih.',
                ],
                'Green Juice' => [
                    'desc' => 'Minuman jus sayuran hijau dan apel dingin tanpa gula tambahan untuk detoksifikasi segar sebelum penerbangan.',
                    'note' => 'Saring jus murni, pasang lid seal anti-tumpah.',
                ],
                'Red Detox Juice' => [
                    'desc' => 'Jus buah bit merah, wortel manis, dan apel segar yang menyegarkan dan memulihkan stamina.',
                    'note' => 'Kemasan botol/cup di-seal rapat dengan mesin press.',
                ],
            ],

            'BAKSO PAK DJO' => [
                'Bakso Campur Pak Djo' => [
                    'desc' => 'Semangkuk bakso campur khas Jawa Timur berisi bakso halus, bakso urat berdaging, tahu bakso empuk, siomay basah, dan kuah kaldu sapi gurih pekat.',
                    'note' => 'Pisahkan kuah kaldu dalam mangkuk anti panas berpenutup kencang. Berikan sambal dan jeruk nipis.',
                ],
                'Ekstra Bakso' => [
                    'desc' => 'Porsi tambahan bakso sapi halus dan urat kenyal gurih siap santap dengan kuah kaldu sapi hangat.',
                    'note' => 'Kemas bakso dalam cup kuah bersegel rapat.',
                ],
            ],

            'HONEY POK' => [
                'Chicken Crispy Bumbu Tabur' => [
                    'desc' => 'Potongan fillet ayam krispi garing keemasan bertabur pilihan bumbu gurih BBQ, keju, atau balado pedas.',
                    'note' => 'Kocok bumbu tabur merata di dalam kantong shaker berventilasi.',
                ],
                'Chicken Crispy Honey Sauce' => [
                    'desc' => 'Ayam krispi renyah tanpa tulang disiram saus madu manis gurih khas Honey Pok yang berkilau lezat.',
                    'note' => 'Saus madu disiram merata sebelum dikemas dalam box take-away.',
                ],
                'Dori Crispy' => [
                    'desc' => 'Fillet ikan dori lembut berbalut tepung renyah garing bertabur bumbu rempah gurih.',
                    'note' => 'Goreng dengan minyak bersih suhu 170°C agar renyah tanpa berminyak.',
                ],
                'Chicken Pok' => [
                    'desc' => 'Camilan potongan daging ayam dadu goreng tepung renyah berukuran bite-sized yang gurih nagih.',
                    'note' => 'Sertakan tusuk kayu bambu higienis.',
                ],
                'Cumi Crispy' => [
                    'desc' => 'Cincin cumi segar kenyal berbalut tepung renyah garing berbumbu lada garam sedap.',
                    'note' => 'Pastikan cumi tidak alot, tiriskan minyak dengan sempurna.',
                ],
                'Jamur Crispy' => [
                    'desc' => 'Jamur tiram goreng tepung krispi keemasan yang renyah gurih, cocok sebagai camilan santai.',
                    'note' => 'Kemas dalam kantong kertas anti-minyak berpori udara.',
                ],
                'Kulit Crispy' => [
                    'desc' => 'Kulit ayam goreng super renyah garing bertabur bumbu gurih gurih asin yang renyah kriuk.',
                    'note' => 'Pastikan kerenyahan kulit maksimal sebelum dibungkus.',
                ],
                'Tahu Crispy Jumbo' => [
                    'desc' => 'Tahu goreng kotak bertepung krispi renyah di luar dan lembut di dalam bertabur bumbu tabur lezat.',
                    'note' => 'Potong dadu rapi, taburi bumbu gurih.',
                ],
                'Cireng Crispy' => [
                    'desc' => 'Olahan tepung tapioka goreng kenyal gurih dengan lapisan luar garing berpadu bumbu cocolan sedap.',
                    'note' => 'Kemas bumbu rujak cocolan dalam cup mini terpisah.',
                ],
                'Kentang Goreng' => [
                    'desc' => 'Kentang goreng potongan lurus renyah keemasan bertabur garam gurih.',
                    'note' => 'Goreng fresh saat dipesan, jangan ditutup rapat.',
                ],
                'Bakso Goreng' => [
                    'desc' => 'Bakso sapi mekar goreng renyah bertekstur kenyal padat bertabur bumbu gurih sedap.',
                    'note' => 'Potong mekar empat sebelum digoreng.',
                ],
                'Singkong Crispy' => [
                    'desc' => 'Singkong goreng merekah empuk gurih berbumbu bawang ketumbar dengan pinggiran garing.',
                    'note' => 'Sajikan hangat dengan cocolan sambal.',
                ],
            ],

            'YOSINOYA' => [
                'Original Beef Bowl' => [
                    'desc' => 'Irisan tipis daging sapi impor US Beef 100% empuk juicy yang dimasak dengan kuah kaldu gurih manis khas resep rahasia Tokyo di atas nasi pulen hangat.',
                    'note' => 'Tata irisan daging sapi di atas nasi pulen Jepang, tutup bowl rapat bersekat.',
                ],
                'Yakiniku Beef Bowl' => [
                    'desc' => 'Nasi pulen Jepang hangat dengan irisan daging sapi empuk berbalut saus yakiniku manis gurih karamel beraroma wijen wangi.',
                    'note' => 'Saus yakiniku dituangkan merata, taburi wijen sangrai di atasnya.',
                ],
                'Black Pepper Beef Bowl' => [
                    'desc' => 'Nasi hangat dengan irisan tipis daging sapi empuk berlumur saus lada hitam gurih pedas manis yang mantap.',
                    'note' => 'Pastikan saus lada hitam meresap sedap ke daging dan nasi.',
                ],
                'Egg Mayo Beef Bowl' => [
                    'desc' => 'Nasi daging sapi original khas Yoshinoya dengan topping saus telur mayones creamy lembut yang lumer lezat.',
                    'note' => 'Saus egg mayo ditata rapi di samping daging sapi.',
                ],
                'Red HOT Chili Beef Bowl' => [
                    'desc' => 'Irisan daging sapi empuk di atas nasi pulen bertabur sambal cabai rawit pedas membakar khas Indonesia.',
                    'note' => 'Kemas sambal cabai rawit dengan rapi di atas daging.',
                ],
                'Creamy Beef Bowl' => [
                    'desc' => 'Nasi sapi gurih dengan siraman saus keju creamy gurih lembut yang meleleh nikmat di atas daging.',
                    'note' => 'Saus keju disiram panas sesaat sebelum bowl ditutup rapat.',
                ],
                'Crispy Spinach Beef Bowl' => [
                    'desc' => 'Nasi sapi original Yoshinoya dengan taburan bayam goreng krispi garing gurih yang renyah kriuk.',
                    'note' => 'Bayam krispi dibungkus plastik terpisah agar tidak melempem terkena uap nasi.',
                ],
                'Black Pepper Veggie Beef Bowl' => [
                    'desc' => 'Daging sapi saus lada hitam gurih berpadu aneka sayuran tumis brokoli wortel renyah segar dan nasi hangat.',
                    'note' => 'Kemas dalam mangkuk bowl tahan panas, sertakan sendok garpu.',
                ],
                'Veggie Beef Bowl' => [
                    'desc' => 'Nasi pulen dengan kombinasi seimbang irisan daging sapi empuk dan sayuran rebus segar kaya nutrisi.',
                    'note' => 'Sayuran direbus fresh agar warna hijau dan oranye tetap segar.',
                ],
            ],

            'KEDAI ES JADOEL' => [
                'Es Campur Kenangan' => [
                    'desc' => 'Minuman penutup legendaris berisi kelapa muda, cincau, kolang-kaling, alpukat, dan tapai manis dalam siraman santan, sirup frambos harum, dan es serut.',
                    'note' => 'Kemas dalam cup take-away besar, seal dengan mesin cup sealer anti bocor.',
                ],
                'Es Oyen Nostalgia' => [
                    'desc' => 'Es campur khas Bandung berisi alpukat mentega, kelapa muda, nangka manis wangi, dan pacar cina dalam kuah susu manis gurih.',
                    'note' => 'Pastikan buah alpukat dan nangka fresh dari chiller.',
                ],
                'Es Teler Rindu' => [
                    'desc' => 'Potongan buah alpukat legit, nangka harum, dan kerokan kelapa muda segar dalam siraman santan manis kental dan susu lezat.',
                    'note' => 'Siramkan santan dan susu kental manis kental merata di atas es.',
                ],
                'Es Cincau Mutiara Jadoel' => [
                    'desc' => 'Potongan cincau hitam herbal sutra berpadu butiran mutiara sagu merah kenyal dan kuah santan gula merah manis gurih.',
                    'note' => 'Pastikan santan segar dan higienis, sediakan sedotan boba jumbo.',
                ],
                'Es Cincau Hitam Jadoel' => [
                    'desc' => 'Cincau hitam alami dingin segar berpadu kuah santan dan sirup manis yang melegakan panas dalam saat perjalanan.',
                    'note' => 'Tutup cup dengan mesin press rapat.',
                ],
                'Es Cincau Hijau Jadoel' => [
                    'desc' => 'Cincau hijau perasan daun alami lembut sutra dalam kuah santan kelapa gurih dan gula merah cair wangi pandan.',
                    'note' => 'Sendokkan cincau hijau perlahan agar tekstur sutranya tidak hancur.',
                ],
                'Es Cincau Mutiara Jadoel Gula Aren' => [
                    'desc' => 'Cincau hitam dan mutiara merah kenyal berpadu kuah gula aren murni pekat yang manis harum alami.',
                    'note' => 'Takaran gula aren disesuaikan standar, aduk sebelum di-seal.',
                ],
                'Es Cincau Hitam Jadoel Gula Aren' => [
                    'desc' => 'Cincau hitam sutra dingin dengan sirup gula aren murni dan santan gurih penyejuk tenggorokan.',
                    'note' => 'Seal cup kencang untuk penumpang transit.',
                ],
                'Es Kelapa Muda Legendaris Gula Aren' => [
                    'desc' => 'Kerokan daging kelapa muda segar beserta air kelapa murni dingin dengan pemanis gula aren alami yang menyehatkan.',
                    'note' => 'Gunakan air kelapa murni dingin tanpa es berlebih.',
                ],
                'Es Kelapa Muda Legendaris' => [
                    'desc' => 'Air kelapa muda segar asli dingin dengan kerokan daging kelapa lembut yang mengembalikan ion tubuh.',
                    'note' => 'Pastikan kelapa muda segar dan cup tersegel rapat.',
                ],
            ],

            'SAMBELAN JUARA' => [
                'Sambelan Empal Goreng' => [
                    'desc' => 'Nasi putih pulen dengan empal daging sapi manis gurih empuk kaya ketumbar berpadu sambal ulek pedas mantap dan lalapan.',
                    'note' => 'Empal dipotong siap makan, sambal ditaruh di wadah tertutup.',
                ],
                'Sambelan Ayam Goreng' => [
                    'desc' => 'Nasi hangat dengan ayam goreng bumbu kuning garing gurih, sambal terasi pedas nampol, dan lalapan timun kol.',
                    'note' => 'Ayam digoreng panas fresh, sertakan tisu basah dan sendok.',
                ],
                'Sambelan Ayam Bakar' => [
                    'desc' => 'Ayam bakar bumbu kecap rempah dengan aroma arang manis gurih meresap, disajikan bersama nasi dan sambal.',
                    'note' => 'Olesi bumbu kecap sebelum dibungkus daun pisang.',
                ],
                'Sambelan Makmur Makan Murah' => [
                    'desc' => 'Paket bersahabat nasi hangat dengan telur dadar tebal gurih, tahu tempe goreng, dan sambal ulek sedap.',
                    'note' => 'Sajikan telur dan tahu tempe dalam keadaan hangat.',
                ],
                'Babat Gongso' => [
                    'desc' => 'Babat sapi empuk gurih yang ditumis dengan bumbu gongso kecap pedas manis beraroma bawang merah wangi khas Semarang.',
                    'note' => 'Kemas tumisan babat dalam wadah anti bocor.',
                ],
                'Cumi Sambel Ijo' => [
                    'desc' => 'Cumi asin kenyal gurih yang ditumis bersama ulekan cabai hijau keriting segar dan irisan tomat hijau asam sedap.',
                    'note' => 'Pastikan minyak sambal ditiriskan aman di dalam kotak makan.',
                ],
                'Opor Ayam' => [
                    'desc' => 'Potongan ayam empuk dalam kuah opor santan putih gurih kaya rempah serai dan lengkuas harum khas Jawa.',
                    'note' => 'Kuah opor dikemas dalam mangkuk sup berpenutup ulir rapat.',
                ],
            ],

            'MIE KLUNTUNG & NASI GORENG JAWA PAK MITRO' => [
                'Nasi Goreng Telur Ambyar' => [
                    'desc' => 'Nasi goreng Jawa tradisional beraroma anglo dengan telur orak-arik ambyar gurih dan bumbu bawang merah sedap.',
                    'note' => 'Kemas dalam lunch box karton tebal, sertakan acar dan kerupuk.',
                ],
                'Nasi Goreng Jawa' => [
                    'desc' => 'Nasi goreng kampung khas Jawa dengan suwiran ayam, kubis segar, kecap manis gurih, dan aroma asap wajan yang nikmat.',
                    'note' => 'Goreng dengan api arang/wajan panas, taburi bawang goreng di atasnya.',
                ],
                'Nasi Goreng Mawut' => [
                    'desc' => 'Perpaduan nasi pulen dan mie kuning kenyal yang digoreng bersama bumbu Jawa gurih manis bertabur suwiran ayam.',
                    'note' => 'Campurkan nasi dan mie secara seimbang.',
                ],
                'Mie Kluntung Kuah' => [
                    'desc' => 'Mie kuning tebal kenyal dalam kuah kaldu ayam kental beraroma udang ebi gurih dengan telur dan sayuran kol sawi segar.',
                    'note' => 'Kuah kaldu mie kluntung WAJIB dipisah dalam wadah sup tertutup kencang.',
                ],
                'Mie Kluntung Goreng' => [
                    'desc' => 'Mie kluntung tebal yang digoreng kecap gurih mantap bertabur suwiran ayam kampung, telur, dan seledri harum.',
                    'note' => 'Sajikan hangat dengan cabai rawit hijau terpisah.',
                ],
                'Mie Kluntung Nyemek' => [
                    'desc' => 'Mie kluntung berkuah kental sedikit (nyemek) yang bumbunya meresap pekat gurih manis memanjakan lidah.',
                    'note' => 'Kemas dalam paper bowl tahan panas ber-seal tape anti tumpah.',
                ],
                'Bihun Goreng Jawa' => [
                    'desc' => 'Bihun beras ditumis bumbu tradisional Jawa manis gurih dengan suwiran ayam, telur, dan taburan bawang goreng renyah.',
                    'note' => 'Pastikan bihun tidak patah-patah saat dimasak.',
                ],
                'Bihun Kuah' => [
                    'desc' => 'Bihun lembut dalam kuah kaldu ayam bening gurih hangat beraroma bawang putih dan rempah merica sedap.',
                    'note' => 'Kuah kaldu dipisah agar bihun tetap kenyal.',
                ],
            ],

            'PEMPEK FARIMA' => [
                'Pempek Kapal Selam Premium' => [
                    'desc' => 'Pempek tenggiri asli porsi jumbo berisi telur ayam utuh berkualitas tinggi, digoreng garing dengan kuah cuko pekat.',
                    'note' => 'Potong simetris, kemas kuah cuko kental dalam botol bersegel anti bocor.',
                ],
                'Pempek Kapal Selam Reguler' => [
                    'desc' => 'Pempek telur utuh goreng garing keemasan kenyal gurih disajikan dengan kuah cuko manis pedas segar.',
                    'note' => 'Cuko dikemas terpisah, sertakan irisan mentimun segar.',
                ],
                'Pempek Kapal Selam Biasa' => [
                    'desc' => 'Pempek kapal selam ukuran standar dengan isian telur gurih dan kuah cuko hitam khas Palembang.',
                    'note' => 'Tiriskan minyak dengan kertas serap sebelum dikemas.',
                ],
                'Pempek Lenjer Premium' => [
                    'desc' => 'Pempek lonjong panjang dari daging ikan tenggiri super kenyal gurih tanpa banyak tepung, digoreng garing.',
                    'note' => 'Potong serong rapi dalam paper bowl take-away.',
                ],
                'Pempek Lenjer Reguler' => [
                    'desc' => 'Pempek lenjer goreng kenyal gurih disajikan dengan irisan timun dan kuah cuko asam pedas manis.',
                    'note' => 'Bungkus cuko rapat dengan plastik klip tebal ganda.',
                ],
                'Pempek Lenjer Biasa' => [
                    'desc' => 'Pempek lenjer porsi sedang dengan rasa ikan tenggiri gurih dan tekstur kenyal nikmat.',
                    'note' => 'Sajikan hangat dengan tusuk atau garpu plastik.',
                ],
                'Batagor Komplit' => [
                    'desc' => 'Olahan tahu dan adonan ikan tenggiri goreng renyah lengkap dengan siomay goreng dan siraman bumbu kacang gurih kental.',
                    'note' => 'Bumbu kacang dipisah dalam cup mini terpisah.',
                ],
                'Batagor Campur' => [
                    'desc' => 'Batagor tahu dan pangsit ikan tenggiri goreng krispi berpadu saus kacang gurih manis pedas dan kecap.',
                    'note' => 'Kemas batagor garing dalam wadah berventilasi.',
                ],
                'Mie Celor Pedas' => [
                    'desc' => 'Mie kuning lembut dalam kuah kental kaldu santan ebi gurih pedas hangat disajikan dengan tauge dan telur rebus.',
                    'note' => 'Kuah kental mie celor dikemas dalam mangkuk sup tahan panas bersegel.',
                ],
                'Tekwan Original' => [
                    'desc' => 'Sup bola-bola ikan tenggiri kenyal lembut dalam kuah kaldu udang bening hangat berpadu jamur kuping dan bengkuang.',
                    'note' => 'Kuah tekwan disajikan mendidih dalam cup sup ber-seal anti bocor.',
                ],
                'Tekwan Tomyam' => [
                    'desc' => 'Inovasi sup tekwan ikan tenggiri dalam kuah tomyam asam pedas segar yang menyegarkan badan.',
                    'note' => 'Pastikan wadah sup tertutup kencang dan aman untuk transit.',
                ],
                'Pempek Crispy' => [
                    'desc' => 'Kulit pempek tipis yang digoreng super renyah garing bertabur aroma ikan gurih, pas untuk camilan krispi.',
                    'note' => 'Kemas dalam kantong kertas snack anti minyak.',
                ],
                'Pempek Tahu' => [
                    'desc' => 'Tahu putih empuk berisikan adonan pempek ikan tenggiri gurih yang digoreng keemasan.',
                    'note' => 'Belah dua secara rapi saat disajikan.',
                ],
                'Pempek Keriting' => [
                    'desc' => 'Pempek bentuk keriting mie kenyal lembut beraroma ikan tenggiri sedap dengan siraman cuko.',
                    'note' => 'Kemas cuko dalam wadah terpisah bertutup rapat.',
                ],
                'Otak-Otak' => [
                    'desc' => 'Otak-otak ikan tenggiri goreng empuk kenyal beraroma daun bawang gurih sedap.',
                    'note' => 'Goreng fresh saat dipesan.',
                ],
                'Kulit Pempek' => [
                    'desc' => 'Pempek kulit ikan tenggiri goreng garing renyah di luar dan kenyal gurih di dalam dengan rasa gurih yang khas.',
                    'note' => 'Tiriskan minyak dengan sempurna.',
                ],
                'Adaan' => [
                    'desc' => 'Pempek bulat berbumbu bawang merah dan santan yang gurih wangi bertekstur kenyal empuk.',
                    'note' => 'Sajikan hangat bersama cuko asam manis pedas.',
                ],
            ],

            'BEBEK GORENG HARISA' => [
                '1/2 Ekor Kremes' => [
                    'desc' => 'Setengah ekor bebek goreng empuk berdaging tebal berbalut taburan kremes renyah garing gurih melimpah dan sambal.',
                    'note' => 'Kremesan dibungkus plastik terpisah agar tetap renyah saat dinikmati di pesawat.',
                ],
                '1/2 Ekor Bebek Muda (Sambal Korek, Rica-Rica, Lombok Ijo)' => [
                    'desc' => 'Setengah ekor bebek muda super empuk lembut dengan pilihan sambal korek pedas, bumbu rica harum, atau cabai hijau segar.',
                    'note' => 'Bungkus bebek dengan daun pisang di dalam kotak kardus tebal.',
                ],
                'Bebek Korek Muda Thailand' => [
                    'desc' => 'Olahan bebek muda empuk dengan bumbu rempah khas dipadu sambal korek cabai rawit pedas nampol.',
                    'note' => 'Kemas sambal korek dalam cup saus bersegel.',
                ],
                'Bebek Lombok Ijo (1 potong)' => [
                    'desc' => 'Potongan bebek goreng gurih berbalut siraman sambal cabai hijau ulek kasar yang pedas segar beraroma daun jeruk.',
                    'note' => 'Siram sambal hijau merata di atas potongan bebek.',
                ],
                'Bebek Putri Madura (1 potong)' => [
                    'desc' => 'Bebek goreng rempah khas Madura berbalut bumbu hitam kental gurih pekat beraroma rempah bakar sedap.',
                    'note' => 'Bumbu hitam Madura dilumuri tebal di atas bebek.',
                ],
                'Bebek Sambal Korek' => [
                    'desc' => 'Bebek goreng renyah empuk berlumur sambal korek bawang pedas membakar yang menggigit lidah.',
                    'note' => 'Sajikan bebek dalam kondisi panas fresh.',
                ],
                'Bebek Korek Sambal Mangga' => [
                    'desc' => 'Bebek goreng krispi berpadu irisan mangga muda segar asam manis pedas yang unik dan menyegarkan.',
                    'note' => 'Sambal mangga dipisah dalam wadah tertutup agar bebek tetap krispi.',
                ],
                'Bebek Goreng (1 potong)' => [
                    'desc' => 'Satu potong paha/dada bebek goreng bumbu ungkep rempah tradisional yang gurih meresap sampai ke serat terdalam.',
                    'note' => 'Kemas rapi dalam lunch box berbahan kardus tebal.',
                ],
                'Nasi Goreng Bebek' => [
                    'desc' => 'Nasi goreng oriental gurih bertabur suwiran daging bebek empuk dan aroma smoky wajan yang lezat.',
                    'note' => 'Goreng fresh saat dipesan, lengkapi dengan acar mentimun.',
                ],
                'Bebek Rica-Rica (1 potong)' => [
                    'desc' => 'Potongan bebek empuk yang dimasak bumbu rica-rica pedas merah menyala kaya serai dan kemangi harum.',
                    'note' => 'Pastikan bumbu rica meresap dan tidak bocor dari box.',
                ],
                'Nasi Bebek Mat Bro' => [
                    'desc' => 'Paket nasi hangat dengan potongan bebek empuk berbalut sambal khas Mat Bro yang gurih pedas mantap.',
                    'note' => 'Tata rapi dalam kotak bento bersekat.',
                ],
            ],

            'SELERA SURABAYA' => [
                'Sop Buntut' => [
                    'desc' => 'Sup buntut sapi impor empuk berdaging tebal dalam kuah kaldu bening berempah pala kapulaga, wortel, dan kentang.',
                    'note' => 'Kuah sup disajikan mendidih dalam wadah sup tertutup rapat dengan seal pelindung.',
                ],
                'Lontong Kikil' => [
                    'desc' => 'Lontong empuk berpadu potongan kikil sapi kenyal gurih dalam siraman kuah kaldu kuning serai yang sedap.',
                    'note' => 'Kemas kuah kikil dalam cup anti bocor, sertakan sambal rawit.',
                ],
                'Nasi Rawon' => [
                    'desc' => 'Nasi putih dengan kuah kluwek hitam pekat khas Surabaya, irisan daging sapi empuk, tauge pendek, dan sambal terasi.',
                    'note' => 'Kuah rawon dikemas rapat tanpa rembes ke luar.',
                ],
                'Lontong Kupang' => [
                    'desc' => 'Makanan khas pesisir Sidoarjo berisi kupang empuk berpadu bumbu petis manis gurih, lontong, dan lentho singkong renyah.',
                    'note' => 'Kemas kuah petis kupang dengan teliti agar tidak bocor.',
                ],
                'Soto Ayam' => [
                    'desc' => 'Soto ayam kuah kuning bening gurih segar beraroma koya dan daun jeruk dengan suwiran ayam melimpah.',
                    'note' => 'Kuah soto dan soun dipisah dalam wadah terpisah.',
                ],
                'Lontong (Ekstra)' => [
                    'desc' => 'Porsi lontong beras empuk padat pelengkap hidangan berkuah.',
                    'note' => 'Potong-potong lontong rapi dan bungkus plastik wrap higienis.',
                ],
            ],

            'BU TOPO RUJAK CINGUR' => [
                'Rujak Cingur Spesial' => [
                    'desc' => 'Rujak cingur khas Surabaya porsi komplit dengan cingur sapi kenyal empuk melimpah, sayuran rebus, lontong, tahu tempe, dan ulekan bumbu petis udang medok sedap.',
                    'note' => 'Ulek bumbu petis fresh saat dipesan, bungkus bumbu terpisah jika untuk perjalanan take-away.',
                ],
                'Rujak Cingur' => [
                    'desc' => 'Rujak khas Jawa Timur berisi potongan cingur sapi kenyal, buah segar mentimun nanas bengkuang, sayur kangkung, dan bumbu petis gurih manis pedas.',
                    'note' => 'Kemas dalam paper bowl tebal, sertakan kerupuk putih.',
                ],
                'Gado-Gado' => [
                    'desc' => 'Sayuran rebus kentang, kubis, tauge, selada, tahu, dan telur rebus disiram saus bumbu kacang gurih manis kental bertabur kerupuk.',
                    'note' => 'Saus kacang dikemas dalam cup saus terpisah.',
                ],
                'Tahu Telor' => [
                    'desc' => 'Dadar telur tahu goreng garing tebal disiram saus petis kecap manis gurih bertabur tauge pendek dan kacang tanah sangrai.',
                    'note' => 'Tiriskan minyak dadar telur tahu, kemas saus petis kecap terpisah.',
                ],
                'Cingur' => [
                    'desc' => 'Porsi ekstra potongan cingur (hidung sapi) rebus empuk kenyal gurih siap santap dengan bumbu petis.',
                    'note' => 'Pastikan cingur bersih, empuk, dan dipotong siap makan.',
                ],
            ],

            'SOTO MADURA WAWAN' => [
                'Soto Campur Istimewa' => [
                    'desc' => 'Soto daging sapi khas Madura porsi istimewa berpadu babat, paru, ati, dan otak empuk dalam kuah kaldu sapi kuning bening gurih pekat.',
                    'note' => 'Kuah kaldu disajikan mendidih dalam mangkuk sup ber-seal anti bocor. Beri jeruk nipis dan sambal.',
                ],
                'Soto Campur Biasa' => [
                    'desc' => 'Soto Madura dengan kuah kaldu sapi kaya rempah berisi potongan daging sapi dan jeroan empuk gurih.',
                    'note' => 'Wadah sup wajib di-seal anti rembes khusus take-away kabin.',
                ],
                'Soto Ati Otak' => [
                    'desc' => 'Porsi hangat kuah soto sapi gurih berisikan ati sapi empuk dan otak sapi lembut lumer beraroma rempah harum.',
                    'note' => 'Tangani otak sapi hati-hati agar tekstur tidak hancur saat dikemas.',
                ],
                'Soto Daging' => [
                    'desc' => 'Soto daging sapi murni tanpa jeroan dengan irisan daging empuk berlimpah dalam kuah kaldu gurih segar.',
                    'note' => 'Pastikan kuah panas saat di-seal, taburi daun bawang dan bawang goreng.',
                ],
                'Soto Babat' => [
                    'desc' => 'Soto khas Madura dengan potongan babat sapi putih kenyal empuk bersih dalam siraman kuah kaldu gurih.',
                    'note' => 'Kemas rapat dalam wadah sup tahan panas.',
                ],
                'Soto Telur Kuah' => [
                    'desc' => 'Kuah soto sapi gurih dengan tambahan telur rebus utuh beraroma bawang putih goreng.',
                    'note' => 'Kemas telur dalam mangkuk sup berkuah kaldu.',
                ],
                'Nasi Putih' => [
                    'desc' => 'Nasi putih pulen hangat pelengkap santapan soto Madura.',
                    'note' => 'Bungkus nasi dengan daun pisang di dalam kotak.',
                ],
                'Telur' => [
                    'desc' => 'Sebutir telur rebus pelengkap kuah soto sapi.',
                    'note' => 'Kupas telur bersih dan masukkan ke wadah plastik higienis.',
                ],
            ],

            'A FUNG' => [
                'Baso Kuah' => [
                    'desc' => 'Bakso sapi gepeng dan bulat khas A Fung berdaging kenyal padat dalam kuah kaldu sapi bening gurih beraroma bawang putih goreng harum.',
                    'note' => 'Kuah kaldu disajikan mendidih, gunakan mangkuk paper cup tebal berpenutup rapat.',
                ],
                'Baso Babat Urat' => [
                    'desc' => 'Kombinasi bakso urat sapi kenyal berdaging dan irisan babat sapi empuk gurih dalam siraman kuah kaldu panas.',
                    'note' => 'Kemas kuah kaldu rapat, berikan sambal cabai rawit khas A Fung.',
                ],
                'Baso Campur' => [
                    'desc' => 'Porsi favorit berisi bakso gepeng, bakso urat, tahu bakso empuk, dan babat sapi dalam kuah kaldu bening gurih lezat.',
                    'note' => 'Susun bakso dan babat rapi dalam mangkuk take-away sebelum disiram kuah panas.',
                ],
                'Tahu Porsi' => [
                    'desc' => 'Tahu putih lembut berisikan adonan daging bakso sapi empuk dalam kuah kaldu sapi hangat.',
                    'note' => 'Kemas tahu bakso hati-hati agar tidak pecah.',
                ],
                'Baso Tahu' => [
                    'desc' => 'Kombinasi seimbang bakso sapi kenyal dan tahu bakso gurih lembut dalam kuah kaldu bening.',
                    'note' => 'Pastikan tutup cup terpasang rapat dan tidak tumpah.',
                ],
                'Baso Goreng' => [
                    'desc' => 'Bakso goreng mekar renyah garing di luar dan kenyal padat di dalam dari olahan daging sapi dan ayam gurih.',
                    'note' => 'Kemas dalam kantong kertas anti-minyak berventilasi udara.',
                ],
                'Babat Urat Porsi' => [
                    'desc' => 'Porsi khusus potongan babat sapi kenyal dan urat sapi empuk dalam siraman kuah sup kaldu gurih panas.',
                    'note' => 'Gunakan wadah sup tahan panas ber-seal tape.',
                ],
                'Bihun' => [
                    'desc' => 'Bihun beras lembut kenyal pelengkap semangkuk bakso kuah A Fung.',
                    'note' => 'Kemas bihun terpisah agar tidak mengembang di kuah kaldu.',
                ],
                'Kwetiau' => [
                    'desc' => 'Kwetiau beras lembut kenyal pelengkap hidangan bakso kuah.',
                    'note' => 'Kemas kwetiau terpisah dalam plastik higienis.',
                ],
                'Nasi Putih' => [
                    'desc' => 'Nasi putih pulen hangat pendamping semangkuk bakso kuah.',
                    'note' => 'Bungkus rapi dalam kotak nasi.',
                ],
                'Tahu Baso Satuan' => [
                    'desc' => 'Satu buah tahu bakso sapi empuk gurih berkualitas tinggi.',
                    'note' => 'Kemas satuan dalam cup mini kuah.',
                ],
                'Baso Goreng 2pcs' => [
                    'desc' => 'Dua butir bakso goreng mekar renyah keemasan beraroma gurih sedap.',
                    'note' => 'Sajikan hangat bersama saus sambal.',
                ],
                'Baso Satuan' => [
                    'desc' => 'Satu butir bakso sapi gepeng atau urat khas A Fung.',
                    'note' => 'Tambahkan ke dalam mangkuk pesanan utama.',
                ],
                'Papercup for Take Away' => [
                    'desc' => 'Kemasan paper cup mangkuk sup tebal khusus take-away tahan panas anti bocor untuk perjalanan kabin pesawat.',
                    'note' => 'Pastikan lid penutup terpasang rapat dan tersegel kencang.',
                ],
            ],

            'AROMA PADANG' => [
                'Nasi Campur Rendang' => [
                    'desc' => 'Nasi rames Padang pulen dengan sepotong rendang daging sapi empuk hitam berempah pekat, sayur singkong, nangka gulai, dan sambal hijau.',
                    'note' => 'Kemas dalam kotak bento bersekat tebal berlapis daun pisang.',
                ],
                'Nasi Campur Kikil Gulai' => [
                    'desc' => 'Nasi Padang hangat lengkap dengan kikil sapi kenyal empuk dalam kuah gulai santan kuning gurih dan sambal.',
                    'note' => 'Kuah gulai dikemas rapat agar tidak merembes ke nasi.',
                ],
                'Nasi Campur Ayam Gulai' => [
                    'desc' => 'Nasi rames dengan sepotong ayam empuk berbalut kuah gulai santan kaya rempah harum khas Ranah Minang.',
                    'note' => 'Lumuri bumbu gulai merata di atas ayam.',
                ],
                'Nasi Campur Ayam Pop' => [
                    'desc' => 'Nasi Padang pulen disajikan dengan ayam pop gurih lembut rebusan air kelapa dan saus cabai tomat merah sedap.',
                    'note' => 'Saus cabai ayam pop dikemas dalam cup saus terpisah.',
                ],
                'Nasi Campur Ayam Panas' => [
                    'desc' => 'Nasi hangat dengan ayam goreng bumbu rempah kuning yang disajikan panas fresh berpadu sayur dan sambal hijau.',
                    'note' => 'Ayam digoreng panas fresh saat pesanan masuk.',
                ],
                'Kikil Gulai' => [
                    'desc' => 'Potongan kikil sapi empuk kenyal dalam siraman kuah gulai santan kuning gurih beraroma daun kunyit.',
                    'note' => 'Kemas kikil gulai dalam mangkuk sup bertutup rapat.',
                ],
                'Otak Gulai' => [
                    'desc' => 'Otak sapi lembut lumer berbalut kuah gulai santan kaya rempah harum khas Minang.',
                    'note' => 'Handle hati-hati saat pengemasan agar otak tidak hancur.',
                ],
                'Ayam Gulai' => [
                    'desc' => 'Potongan ayam ungkep bumbu gulai yang empuk dalam siraman kuah santan kuning gurih beraroma serai.',
                    'note' => 'Pastikan wadah tertutup rapat.',
                ],
                'Ayam Pop' => [
                    'desc' => 'Ayam kampung pejantan muda lembut tanpa kulit yang gurih alami disajikan dengan saus cabai cocolan khas.',
                    'note' => 'Sertakan cup saus cabai merah khas ayam pop.',
                ],
                'Telur Barendo' => [
                    'desc' => 'Telur dadar khas Minang berenda garing krispi renyah di tepian bertabur irisan daun bawang dan cabai merah.',
                    'note' => 'Kemas dalam wadah kering agar kerenyahan renda telur terjaga.',
                ],
                'Cumi Gulai' => [
                    'desc' => 'Cumi laut segar empuk berisikan tahu telur dalam kuah gulai kemerahan kaya rempah kapulaga.',
                    'note' => 'Kemas cumi gulai dengan kuah kental dalam cup bersekat.',
                ],
                'Soto Padang' => [
                    'desc' => 'Soto sapi kuah bening kaya rempah khas Padang dengan irisan dendeng sapi garing, perkedel kentang, dan kerupuk merah.',
                    'note' => 'Dendeng garing dan kerupuk merah dikemas terpisah dari kuah soto bening panas.',
                ],
                'Sup Buntut' => [
                    'desc' => 'Buntut sapi berdaging empuk dalam kuah kaldu bening gurih kaya rempah pala dan cengkeh.',
                    'note' => 'Gunakan cup sup tahan panas bertutup rapat.',
                ],
                'Sup Iga' => [
                    'desc' => 'Iga sapi berdaging tebal empuk dalam kuah sup rempah hangat berpadu wortel dan seledri.',
                    'note' => 'Pastikan tulang iga muat pas di dalam wadah tanpa menusuk penutup.',
                ],
            ],

            'DONUT KINGS' => [
                'Ice Coffee Latte' => [
                    'desc' => 'Kopi espresso dingin berpadu susu segar creamy seimbang beralaskan es batu penyegar dahaga.',
                    'note' => 'Tutup cup dengan lid anti-tumpah, sediakan sedotan ramah lingkungan.',
                ],
                'Ice Caramel Macchiato' => [
                    'desc' => 'Susu dingin lembut berpadu shot espresso mantap dan lumuran saus karamel manis wangi.',
                    'note' => 'Tuang saus karamel di atas lapisan es susu.',
                ],
                'Ice Cappuccino' => [
                    'desc' => 'Kopi espresso dingin dengan susu segar dan lapisan busa susu tebal lembut bertabur bubuk cokelat.',
                    'note' => 'Taburi bubuk cokelat tipis sebelum di-seal.',
                ],
                'Ice Vanilla Latte' => [
                    'desc' => 'Minuman kopi susu dingin lembut dengan aroma sirup vanila manis harum yang menenangkan.',
                    'note' => 'Pastikan sirup vanila tercampur rata.',
                ],
                'Ice Strawberry Latte' => [
                    'desc' => 'Susu dingin rasa stroberi manis segar berpadu shot espresso lembut yang menyegarkan perjalanan.',
                    'note' => 'Buat layer warna menarik antara stroberi dan kopi.',
                ],
                'Ice Mocha' => [
                    'desc' => 'Paduan espresso dingin, saus cokelat pekat, dan susu segar creamy dengan rasa manis gurih mantap.',
                    'note' => 'Aduk rata cokelat dengan espresso.',
                ],
                'Ice Lychee Tea' => [
                    'desc' => 'Teh hitam dingin segar dengan sari buah leci manis harum dan buah leci asli yang menyegarkan.',
                    'note' => 'Masukkan 2 butir buah leci utuh dalam cup.',
                ],
                'Cappuccino (REG)' => [
                    'desc' => 'Kopi cappuccino hangat berbusa microfoam tebal lembut berpadu espresso mantap bertabur cokelat bubuk.',
                    'note' => 'Gunakan cup panas double-wall dengan sleeve tahan panas.',
                ],
                'Coffee Latte (REG)' => [
                    'desc' => 'Kopi espresso klasik hangat dengan steamed milk bertekstur halus dan rasa seimbang.',
                    'note' => 'Sajikan hangat pada suhu 65°C.',
                ],
                'Caramel Macchiato (REG)' => [
                    'desc' => 'Minuman kopi hangat dengan lapisan susu vanila, espresso bold, dan pola saus karamel lezat.',
                    'note' => 'Drizzle saus karamel di atas busa susu.',
                ],
                'Long Black (REG)' => [
                    'desc' => 'Kopi hitam hangat double shot espresso di atas air panas dengan lapisan crema cokelat keemasan tebal.',
                    'note' => 'Tuang espresso perlahan di atas air panas agar crema tetap utuh.',
                ],
                'Chocolate (REG)' => [
                    'desc' => 'Minuman cokelat panas kental nikmat dari cokelat pilihan berpadu susu hangat yang manis menenangkan.',
                    'note' => 'Aduk hingga larut kental sempurna.',
                ],
                'Combo Donut + Regular Long Black' => [
                    'desc' => 'Paket pairing hemat 1 donat lembut aneka topping favorit plus 1 cup kopi hitam Long Black hangat/dingin.',
                    'note' => 'Kemas donat dalam kotak pastry khusus dan amankan cup kopi di cup holder.',
                ],
                'Combo Donut + Regular Coffee Latte' => [
                    'desc' => 'Paket sarapan santai 1 donat manis empuk lezat berpadu 1 cup Coffee Latte creamy segar.',
                    'note' => 'Pastikan donat tidak tertindih cup kopi di dalam tas jinjing.',
                ],
            ],

            'SUBWAY' => [
                'Italian B.M.T' => [
                    'desc' => 'Roti sandwich lapis tiga daging salami gurih, pepperoni renyah berempah, dan lembaran smoked chicken breast dengan keju leleh dan sayuran segar.',
                    'note' => 'Bungkus rapat dengan subway paper wrap, beri stiker penanda varian daging dan saus.',
                ],
                'Roast Beef' => [
                    'desc' => 'Roti sandwich dengan irisan daging sapi panggang empuk premium berpadu saus pilihan dan sayuran selada timun renyah.',
                    'note' => 'Panggang roti 20 detik jika diminta toasted, potong miring menjadi dua bagian.',
                ],
                'Steak & Cheese' => [
                    'desc' => 'Sandwich dengan potongan daging steak sapi empuk berlumur lelehan keju cheddar gurih dan tumisan paprika bombay harum.',
                    'note' => 'Toast hingga keju meleleh sempurna dan daging steak panas gurih.',
                ],
                'Chicken Teriyaki' => [
                    'desc' => 'Fillet daging ayam empuk berbalut saus teriyaki manis gurih khas Jepang dengan sayuran segar dalam roti gandum hangat.',
                    'note' => 'Lumuri saus teriyaki merata sebelum ditumpuk sayuran segar.',
                ],
                'Chicken Slice' => [
                    'desc' => 'Sandwich roti gandum lembut dengan lembaran irisan daging ayam tipis rendah lemak, keju, dan sayuran renyah.',
                    'note' => 'Susun lembaran ayam rapi dan seimbang di sepanjang roti.',
                ],
                'Tuna Mayo' => [
                    'desc' => 'Olahan daging ikan tuna lembut berpadu mayones gurih creamy dan sayuran segar berbalut roti pilihan yang dipanggang hangat.',
                    'note' => 'Ratakan tuna mayo di dasar roti, beri sayuran selada dan tomat segar.',
                ],
                'Egg Mayo' => [
                    'desc' => 'Roti sandwich lembut dengan isian telur rebus orak-arik cincang berpadu saus mayones creamy yang manis gurih lembut.',
                    'note' => 'Kemas rapat dalam wrapper kertas wax higienis.',
                ],
                'Chicken Classic' => [
                    'desc' => 'Sandwich ayam panggang klasik dengan daging ayam lembut beraroma bumbu ringan dan sayuran segar renyah.',
                    'note' => 'Tambahkan pilihan saus mayones/mustard sesuai standar.',
                ],
                'Veggie Delite' => [
                    'desc' => 'Sandwich vegetarian segar berisi kombinasi selada, tomat, mentimun, paprika hijau, dan zaitun berbalut roti renyah.',
                    'note' => 'Pastikan sayuran dicuci bersih, renyah, dan segar.',
                ],
                'Meatball Marinara' => [
                    'desc' => 'Roti hangat berisikan bola-bola daging sapi empuk berlumur saus tomat marinara gurih manis beraroma oregano Italia.',
                    'note' => 'Tata bola daging hati-hati agar saus marinara tidak meluber ke luar bungkus.',
                ],
                'Spicy Italian' => [
                    'desc' => 'Kombinasi irisan pepperoni pedas berempah dan salami gurih dengan lelehan keju dan sayuran segar.',
                    'note' => 'Toasted roti hingga keju meleleh dan pepperoni mengeluarkan minyak gurih.',
                ],
                'Subway Melt' => [
                    'desc' => 'Sandwich lapis bertabur irisan daging ayam, ham sapi, daging bacon renyah, dan keju leleh nikmat.',
                    'note' => 'Bungkus rapi, sertakan tisu makan Subway.',
                ],
                'Chocolate Chip Cookie' => [
                    'desc' => 'Kukis khas Subway bertekstur lembut chewy di tengah dengan lelehan butiran cokelat chip manis gurih.',
                    'note' => 'Kemas satuan dalam kantong kertas kue Subway.',
                ],
                'Macaroni & Cheese' => [
                    'desc' => 'Pasta makaroni lembut berbalut saus keju cheddar gurih kental yang lumer creamy hangat.',
                    'note' => 'Hangatkan sebelum diserahkan, tutup wadah mangkuk rapat.',
                ],
                'Hash Brown' => [
                    'desc' => 'Kentang goreng serut segitiga renyah keemasan di luar dan lembut gurih di dalam.',
                    'note' => 'Panggang hingga renyah garing, tiriskan dengan kertas penyerap.',
                ],
            ],

            'STARBUCKS' => [
                'Caramel Macchiato' => [
                    'desc' => 'Susu steamed segar dengan sirup vanila berpadu shot espresso pekat di atasnya dan pola saus karamel manis legit khas Starbucks.',
                    'note' => 'Tuang saus karamel dalam pola kisi-kisi di atas lapisan busa susu, pasang lid anti tumpah.',
                ],
                'Caffè Latte' => [
                    'desc' => 'Minuman kopi espresso klasik berpadu susu segar steamed bersuhu pas dengan lapisan busa mikro tipis yang lembut.',
                    'note' => 'Sajikan hangat pada suhu 65°C dengan sleeve karton pelindung tangan.',
                ],
                'Cappuccino' => [
                    'desc' => 'Kopi espresso berkarakter tegas dengan steamed milk dan lapisan foam tebal bertekstur sutra bertabur bubuk cokelat.',
                    'note' => 'Taburi bubuk cokelat tipis di atas foam sebelum ditutup lid.',
                ],
                'Caffè Mocha' => [
                    'desc' => 'Paduan espresso kaya rasa dengan saus cokelat moka pekat manis gurih, susu hangat, dan whipped cream tebal.',
                    'note' => 'Aduk saus moka dengan espresso sebelum susu dituangkan, beri topping whipped cream jika diminta.',
                ],
                'Americano' => [
                    'desc' => 'Double shot espresso kaya rasa yang dilarutkan air mineral murni, menghasilkan rasa kopi yang bersih, bold, dan menyegarkan.',
                    'note' => 'Tutup cup dengan lid klip penahan tumpah untuk penumpang kabin pesawat.',
                ],
                'Flat White' => [
                    'desc' => 'Perpaduan ristretto espresso mantap dan steamed whole milk halus bertekstur velvety dengan titik busa putih di tengahnya.',
                    'note' => 'Tuang microfoam presisi agar menghasilkan dot putih di tengah cangkir.',
                ],
                'Caramel Frappuccino' => [
                    'desc' => 'Minuman es blender kopi khas Starbucks berpadu sirup karamel lezat, susu, dan es batu halus dengan whipped cream dan saus karamel.',
                    'note' => 'Semprotkan whipped cream swirl dan siram saus karamel di atasnya, pasang dome lid.',
                ],
                'Java Chip Frappuccino' => [
                    'desc' => 'Frappuccino kopi dengan saus moka, butiran cokelat java chip renyah, susu, dan es halus bertabur whipped cream.',
                    'note' => 'Pastikan butiran java chip terblender pas menyisakan sensasi renyah saat disedot.',
                ],
                'Mocha Frappuccino' => [
                    'desc' => 'Minuman es blender kopi dan saus cokelat kakao pekat dengan susu dan es batu halus bertabur krim kocok lembut.',
                    'note' => 'Drizzle saus moka di atas whipped cream, sediakan sedotan hijau besar.',
                ],
                'Chocolate Chip Cream Frappuccino' => [
                    'desc' => 'Minuman non-kopi es blender rasa cokelat manis creamy berpadu butiran cokelat chip renyah dan whipped cream.',
                    'note' => 'Pastikan takaran es dan susu seimbang, pasang dome lid rapat.',
                ],
                'Matcha Cream Frappuccino' => [
                    'desc' => 'Minuman es blender teh hijau matcha Jepang premium manis berpadu susu segar creamy dan topping whipped cream tebal.',
                    'note' => 'Blender hingga warna hijau cerah merata tanpa gumpalan bubuk.',
                ],
                'Strawberry Açaí Refreshers' => [
                    'desc' => 'Minuman dingin pelepas dahaga dari sari buah stroberi manis asam segar berpadu ekstrak açaí dan potongan buah stroberi asli.',
                    'note' => 'Kocok dengan shaker es bersama irisan stroberi kering asli.',
                ],
                'Pink Drink with Strawberry Açaí' => [
                    'desc' => 'Kombinasi segar Strawberry Açaí Refreshers dengan susu kelapa (coconut milk) creamy manis segar bernuansa merah muda.',
                    'note' => 'Shake hingga warna merah muda pastel merata sempurna, sajikan dingin.',
                ],
                'Mango Dragonfruit Refreshers' => [
                    'desc' => 'Minuman tropis dingin dari sari mangga manis dan buah naga ungu segar bertabur potongan buah naga asli.',
                    'note' => 'Shake rata bersama es dan potongan buah naga ungu kering.',
                ],
                'Chocolate Cream Chip Frappuccino' => [
                    'desc' => 'Frappuccino cokelat creamy pekat tanpa kopi berpadu butiran cokelat renyah dan siraman saus cokelat lezat.',
                    'note' => 'Hias dinding dalam cup dengan saus cokelat sebelum menuangkan frappuccino.',
                ],
            ],

            'BAKMI GOCIT' => [
                'Bakmi Rempah Spesial' => [
                    'desc' => 'Mie kenyal khas Gocit dengan racikan rempah istimewa, topping ayam cincang gurih, potongan jamur, dan taburan daun bawang segar.',
                    'note' => 'Pisahkan kuah kaldu dalam mangkuk anti-bocor terpisah. Sertakan sumpit, sendok, dan sambal sachet.',
                ],
                'Bakmi Pangsit' => [
                    'desc' => 'Mie keriting kenyal disajikan dengan pangsit rebus lembut isi daging ayam berbumbu sedap serta kuah kaldu gurih hangat.',
                    'note' => 'Pangsit kuah dikemas terpisah agar kulit tidak mudah sobek saat dibawa penumpang menuju boarding gate.',
                ],
                'Bakmi Ayam Jamur' => [
                    'desc' => 'Pilihan favorit mie kenyal berpadu topping tumis ayam jamur manis gurih dengan kuah kaldu ayam bening yang lezat.',
                    'note' => 'Pastikan kuah kaldu di-seal rapat dengan lid anti tumpah. Sertakan tisu dan alat makan.',
                ],
                'Bakmi Bakso Sapi' => [
                    'desc' => 'Bakmi lembut kenyal disajikan lengkap dengan bakso sapi kenyal berdaging padat dan kuah kaldu sapi gurih beraroma bawang putih.',
                    'note' => 'Bakso dan kuah dipisah dalam mangkuk cup berpenutup rapat. Sajikan panas.',
                ],
                'Bakmi Ayam' => [
                    'desc' => 'Sajian klasik bakmi gurih bertekstur kenyal dengan potongan daging ayam empuk berbumbu rempah tradisional khas Gocit.',
                    'note' => 'Cek takaran bumbu minyak bakmi agar pas dan tidak berminyak berlebih di dalam paper bowl.',
                ],
                'Bakmi Sapi Lada Hitam' => [
                    'desc' => 'Mie kenyal berpadu irisan daging sapi empuk yang ditumis dengan saus lada hitam pekat yang hangat, gurih, dan pedas manis.',
                    'note' => 'Kemas daging sapi dan saus lada hitam di atas mie sebelum di-seal, pastikan tutup bowl rapat.',
                ],
                'Bakmi Sukiakuw' => [
                    'desc' => 'Inovasi bakmi dengan aneka olahan suki lezat berpadu kuah kaldu harum dan bumbu khas oriental yang memanjakan lidah.',
                    'note' => 'Beri wadah double-wall anti panas untuk kuah suki, pastikan topping tertata rapi.',
                ],
                'Bakmi Hijau Ayam Sukiakuw' => [
                    'desc' => 'Mie hijau sehat dari ekstrak sayuran alami dengan topping suki lezat dan potongan daging ayam berbumbu khas yang gurih nikmat.',
                    'note' => 'Pastikan mie hijau ditiriskan sempurna sebelum diracik agar warna dan tekstur tetap segar.',
                ],
                'Bakmi Ayam Charsiu' => [
                    'desc' => 'Bakmi gurih dengan topping irisan daging ayam charsiu merah manis gurih beraroma smoky oriental yang menggugah selera.',
                    'note' => 'Irisan charsiu diletakkan rapi di atas mie, tambahkan garnish daun bawang segar.',
                ],
                'Tahu Bakso + Bakso' => [
                    'desc' => 'Porsi hangat kombinasi tahu bakso empuk gurih dan bakso sapi kenyal dalam siraman kuah kaldu bening yang kaya rasa.',
                    'note' => 'Sajikan mendidih, wadah sup wajib di-seal anti bocor khusus take-away kabin.',
                ],
                'Bakmi Lebar Spesial' => [
                    'desc' => 'Mie lebar pipih kenyal berpadu rempah racikan khas Gocit dan topping ayam komplit yang memuaskan nafsu makan.',
                    'note' => 'Pastikan mie lebar diaduk rata dengan minyak bumbu sebelum ditutup agar tidak saling menempel saat transit.',
                ],
                'Nasi Goreng Ayam Spesial' => [
                    'desc' => 'Nasi goreng oriental wangi berasap (wok-hei) dengan suwiran ayam, telur orak-arik, dan bumbu kecap gurih pas di lidah.',
                    'note' => 'Kemas dalam lunch box tebal, sertakan acar segar dan kerupuk dalam plastik klip terpisah.',
                ],
                'Bakmi Goreng Spesial' => [
                    'desc' => 'Bakmi goreng bercita rasa manis gurih mantap bertabur potongan ayam, sayuran renyah, telur, dan aroma smokey yang sedap.',
                    'note' => 'Sajikan hangat, masukkan kerupuk dan sambal dalam kemasan terpisah agar tetap garing.',
                ],
                'Ifumie Ayam' => [
                    'desc' => 'Mie kuning garing renyah yang disiram kuah kental hangat berisi daging ayam, bakso, sayuran hijau, dan wortel manis gurih.',
                    'note' => 'Mie garing WAJIB dibungkus terpisah dari siraman kuah capcay kental agar tidak lembek di jalan.',
                ],
                'Nasi Sapi Lada Hitam' => [
                    'desc' => 'Nasi putih pulen hangat dengan irisan daging sapi empuk berbalut saus lada hitam gurih pekat dan potongan paprika renyah.',
                    'note' => 'Daging sapi dan saus disiram di atas nasi pulen, tutup box rapat dan lapisi kantong take-away bersih.',
                ],
            ],

            'KILLINEY' => [
                'Nasi Lemak With Chicken Katsu' => [
                    'desc' => 'Nasi lemak harum santan daun pandan dipadu potongan chicken katsu fillet renyah keemasan, sambal bilis pedas gurih, dan telur.',
                    'note' => 'Katsu ayam dipotong membujur rapi. Kemas sambal bilis dalam wadah terpisah agar katsu tetap renyah garing.',
                ],
                'Rendang Ayam Rice' => [
                    'desc' => 'Nasi putih pulen disajikan dengan potongan daging ayam empuk berbalut bumbu rendang Minang-Melayu yang pekat dan harum rempah.',
                    'note' => 'Tuang bumbu rendang tebal di atas ayam, lengkapi dengan irisan timun segar dan sendok garpu.',
                ],
                'Hainan Chicken Rice' => [
                    'desc' => 'Nasi hainan harum kaldu ayam jahe bertabur bawang putih cincang, disajikan dengan ayam rebus lembut juicy, saus jahe, dan saus cabai.',
                    'note' => 'Saus jahe, kecap asin aromatik, dan saus cabai hainan dikemas dalam cup bumbu bersekat terpisah.',
                ],
                'Chicken Leg Soy With Rice' => [
                    'desc' => 'Nasi putih hangat dengan paha ayam utuh lembut berkuah semur kecap oriental manis gurih yang meresap sempurna hingga ke tulang.',
                    'note' => 'Masukkan paha ayam dan siraman kuah kecap dalam mangkuk bento anti bocor.',
                ],
                'Beef Blackpepper With Rice' => [
                    'desc' => 'Nasi hangat dengan potongan daging sapi empuk tumis saus lada hitam harum berpadu irisan bawang bombay dan paprika manis.',
                    'note' => 'Pastikan saus lada hitam kental dan tidak bocor, tutup kotak makan dengan kencang.',
                ],
                'Fried Rice' => [
                    'desc' => 'Nasi goreng oriental khas Killiney dengan wangi tumisan khas, potongan telur, ayam cincang, dan bumbu gurih yang sedap pas.',
                    'note' => 'Goreng dengan api besar agar aroma wok-hei keluar. Kemas bersama acar mentimun dan kerupuk.',
                ],
                'Mee Goreng' => [
                    'desc' => 'Mie kuning kenyal ditumis dengan bumbu saus khas mamak, sayuran renyah, tahu, tauge segar, dan sentuhan pedas manis gurih.',
                    'note' => 'Tambahkan irisan jeruk nipis segar di sudut box take-away.',
                ],
                'Kwetiaw Goreng' => [
                    'desc' => 'Kwetiau beras lebar lembut kenyal berpadu telur orak-arik, bakso, ayam, dan tauge segar dengan bumbu kecap gurih beraroma smoky.',
                    'note' => 'Masak fresh saat dipesan, pastikan tauge tetap bertekstur krispi segar saat dikemas.',
                ],
                'Curry Chicken With Rice' => [
                    'desc' => 'Nasi pulen berpadu kuah kari ayam kental beraroma rempah kapulaga kayu manis yang sedap dengan potongan ayam empuk dan kentang lembut.',
                    'note' => 'Kuah kari dipisahkan dalam mangkuk berpenutup rapat agar nasi tidak terlalu lembek saat dibawa penumpang.',
                ],
                'Asam Fish' => [
                    'desc' => 'Fillet ikan lembut dalam kuah asam pedas bumbu belimbing wuluh dan rempah Melayu yang segar menggoyang lidah.',
                    'note' => 'Pastikan wadah sup tertutup rapat dan dilapisi kantong plastik khusus sup anti tumpah.',
                ],
                'Mee Laksa' => [
                    'desc' => 'Mie kuning lembut dalam kuah laksa santan berempah merah gurih beraroma ebi, disajikan dengan udang, telur rebus, dan tahu pong.',
                    'note' => 'Kuah laksa WAJIB dipisah dari mie dalam wadah sup seal anti tumpah.',
                ],
                'Butter Kaya Toast' => [
                    'desc' => 'Roti panggang garing keemasan dengan lapisan selai srikaya manis harum pandan dan potongan mentega dingin gurih yang meleleh nikmat.',
                    'note' => 'Potong roti jadi dua bagian simetris, bungkus dengan kertas roti pembungkus panas agar mentega tidak leleh keluar.',
                ],
                'Kaya Toast' => [
                    'desc' => 'Roti panggang tipis renyah klasik khas Singapura dengan olesan selai srikaya telur manis lembut yang legit dan harum.',
                    'note' => 'Sajikan fresh dari pemanggang, kemas rapi dalam kantong kertas snack Killiney.',
                ],
                'Coffee C' => [
                    'desc' => 'Kopi hitam tarikan tradisional berpadu susu evaporasi lembut tanpa tambahan gula berlebih, menghasilkan rasa kopi yang nutty dan halus.',
                    'note' => 'Saring kopi dengan kaos saring kopi tradisional, gunakan lid anti tumpah bertutup klip.',
                ],
                'Milo Dinosaur' => [
                    'desc' => 'Minuman cokelat malt Milo dingin creamy pekat dengan taburan bubuk Milo melimpah di atasnya yang renyah legit di mulut.',
                    'note' => 'Taburkan dua sendok makan penuh bubuk Milo kering di atas es tepat sebelum cup ditutup lid dome.',
                ],
            ],

            'LE PETIT JEMMA' => [
                'Pain Au Chocolat' => [
                    'desc' => 'Pastry gulung khas Prancis berlapis butter renyah keemasan dengan dua batang cokelat hitam premium Prancis yang lumer manis di dalamnya.',
                    'note' => 'Gunakan jepitan kue saat memindahkan pastry ke dalam paper box agar serpihan flaky tidak rontok.',
                ],
                'Triple Cheese Puff' => [
                    'desc' => 'Puff pastry renyah garing berisikan lelehan perpaduan keju cheddar, mozzarella gurih mulur, dan keju parmesan aromatik.',
                    'note' => 'Hangatkan selama 30 detik sebelum di-pack untuk penumpang yang ingin menikmati sensasi keju mulur.',
                ],
                'Almond Croissant' => [
                    'desc' => 'Croissant mentega renyah dengan isian krim almond frangipane manis harum, bertabur irisan kacang almond panggang dan gula halus.',
                    'note' => 'Kemas dalam box croissant khusus berpenutup mika bening agar bentuknya tetap cantik.',
                ],
                'Chicken BBQ Sandwich' => [
                    'desc' => 'Roti artisan ciabatta renyah lembut dengan isian dada ayam panggang saus BBQ manis smoky, selada renyah, dan irisan tomat segar.',
                    'note' => 'Potong diagonal dan bungkus dengan kertas wax sandwich agar mudah disantap penumpang di pesawat.',
                ],
                'Ham & Cheese Sandwich' => [
                    'desc' => 'Roti sandwich gandum berisikan irisan smoked beef ham gurih, lembaran keju cheddar lembut, dan olesan saus mayones mustard lezat.',
                    'note' => 'Kemas rapat dalam kotak segitiga sandwich, sediakan tisu makan.',
                ],
                'Raisin Danish' => [
                    'desc' => 'Pastry gulung bundar renyah bertabur kismis manis segar dengan olesan glaze aprikot berkilau dan rasa butter yang kaya.',
                    'note' => 'Beri alas kertas kue di bagian bawah dan kemas satuan.',
                ],
                'Choux Bread' => [
                    'desc' => 'Roti bertekstur unik perpaduan roti lembut dengan lapisan choux pastry renyah di atasnya dan isian krim vanila lembut dingin.',
                    'note' => 'Pastikan disimpan dalam suhu dingin showcase sebelum dikemas agar krim vanila tetap segar.',
                ],
                'Coffee Bread' => [
                    'desc' => 'Roti bun lembut beraroma harum kopi moka dengan lapisan topping renyah dan isian butter gurih manis yang meleleh di lidah.',
                    'note' => 'Sajikan hangat, masukkan ke kantong kertas kedap minyak.',
                ],
                'Caffe Latte' => [
                    'desc' => 'Minuman kopi espresso klasik berpadu susu steamed velvety halus dengan rasa seimbang dan busa mikro lembut.',
                    'note' => 'Gunakan cup panas double-wall, pasang lid penutup dan sleeve pelindung tangan.',
                ],
                'Cappuccino' => [
                    'desc' => 'Kopi espresso berkarakter mantap dengan perpaduan susu panas seimbang dan lapisan busa susu tebal lembut bertabur cokelat.',
                    'note' => 'Tuang espresso dan buih susu secara presisi, taburi bubuk cokelat di atas foam.',
                ],
                'Hot Chocolate' => [
                    'desc' => 'Cokelat panas kental nikmat dari bubuk kakao murni pilihan berpadu susu segar hangat yang manis creamy menenangkan jiwa.',
                    'note' => 'Aduk hingga larut kental, pastikan suhu saji 65-70°C sebelum dipasang lid anti-panas.',
                ],
                'Iced Latte' => [
                    'desc' => 'Minuman espresso segar dingin dipadu susu segar creamy beralaskan es batu, menyegarkan suasana perjalanan di bandara.',
                    'note' => 'Seal cup menggunakan mesin cup sealer atau lid dome anti-bocor, sertakan sedotan ramah lingkungan.',
                ],
                'Iced Chocolate' => [
                    'desc' => 'Minuman cokelat dingin pekat yang kaya rasa berpadu susu segar manis sejuk, favorit segala usia sebelum terbang.',
                    'note' => 'Kocok rata dengan shaker es sebelum dituangkan ke cup take-away.',
                ],
                'Matcha Latte' => [
                    'desc' => 'Minuman teh hijau matcha premium Jepang yang dipadukan dengan susu segar manis creamy, beraroma harum menenangkan.',
                    'note' => 'Larutkan bubuk matcha dengan air hangat dulu hingga larut sebelum dipadukan dengan susu dingin/hangat.',
                ],
                'Coffee Latte Signature' => [
                    'desc' => 'Racikan kopi latte spesial Le Petit Jemma dengan sirup rahasia beraroma karamel vanila lembut dan espresso pilihan.',
                    'note' => 'Pastikan takaran sirup signature sesuai takaran standar resep, aduk perlahan dan tutup rapat.',
                ],
            ],

            'PAFU' => [
                'Iced Earl Grey Matcha' => [
                    'desc' => 'Minuman fusion segar perpaduan seduhan teh Earl Grey beraroma bergamot elegan dengan matcha latte creamy yang lembut di lidah.',
                    'note' => 'Tuang berlayer antara earl grey dan foam matcha dingin, seal cup rapat dan sediakan sedotan.',
                ],
                'Iced Pure Matcha Latte' => [
                    'desc' => 'Susu segar berpadu bubuk green tea matcha asli tanpa pemanis berlebih, menghadirkan cita rasa teh hijau Jepang yang creamy otentik.',
                    'note' => 'Whisk bubuk matcha hingga berbusa halus, tuangkan ke atas susu segar dingin beralas es batu.',
                ],
                'Iced Coconut Matcha' => [
                    'desc' => 'Kombinasi eksotis air kelapa murni segar yang manis alami dengan siraman pure matcha pekat yang kaya antioksidan.',
                    'note' => 'Buat tampilan layer cantik antara lapisan bening kelapa di bawah dan matcha pekat di atas.',
                ],
                'Iced Taro Latte' => [
                    'desc' => 'Minuman manis lembut berbahan ubi ungu taro aromatik berpadu susu segar creamy yang menenangkan dan menyegarkan.',
                    'note' => 'Shake hingga tekstur taro larut sempurna dan warna ungu pastel terlihat memikat.',
                ],
                'Iced Banana Taro Milk' => [
                    'desc' => 'Perpaduan unik manis lembut buah pisang tropis dan rasa taro yang creamy dalam balutan susu segar dingin yang nagih.',
                    'note' => 'Pastikan sirup pisang dan taro menyatu lembut, beri penutup seal anti bocor.',
                ],
                'Apple Puff' => [
                    'desc' => 'Puff pastry renyah bermentega emas dengan isian potongan buah apel karamel manis asam segar dan aroma kayu manis harum.',
                    'note' => 'Masukkan ke dalam box puff khusus menggunakan jepitan pastry agar lapisan pastry yang flaky tidak hancur.',
                ],
                'Choco Lovers Puff' => [
                    'desc' => 'Pastry garing renyah bertabur gula halus dengan lelehan krim cokelat lumer pekat premium di setiap gigitan.',
                    'note' => 'Kemas hati-hati, pastikan isian cokelat tidak meluber ke sisi luar kotak.',
                ],
                'Strawberry Puff' => [
                    'desc' => 'Puff pastry lapis renyah keemasan dengan isian krim selai stroberi segar manis asam yang lumer di mulut.',
                    'note' => 'Berikan alas kertas roti di dalam box take-away agar kelembapan terjaga.',
                ],
                'Blueberry Cheese Puff' => [
                    'desc' => 'Pastry renyah beraroma butter dengan kombinasi selai blueberry manis segar dan krim keju gurih creamy yang lumer lumer sedap.',
                    'note' => 'Sajikan fresh dari showcase, pastikan pastry tetap kokoh dan tidak tergencet.',
                ],
                'Banana Caramel Puff' => [
                    'desc' => 'Puff pastry renyah garing berisikan potongan pisang manis legit berbalut saus karamel wangi yang menggoda selera.',
                    'note' => 'Pastikan karamel tidak tumpah, susun puff dalam box takeaway bersekat.',
                ],
                'Mochi Pistachio Puff' => [
                    'desc' => 'Inovasi pastry renyah dengan isian mochi kenyal lembut dan pasta kacang pistachio gurih creamy yang wangi mewah.',
                    'note' => 'Handle dengan hati-hati menggunakan sarung tangan higienis, kemas satuan dalam kantong kaca transparan.',
                ],
                'Smoked Beef & Cheese Puff' => [
                    'desc' => 'Puff pastry gurih renyah berlapis dengan isian irisan daging sapi asap premium dan lelehan keju cheddar gurih nikmat.',
                    'note' => 'Hangatkan 45 detik sebelum diserahkan jika diminta panas oleh penumpang.',
                ],
                'Truffle Mushroom Puff' => [
                    'desc' => 'Pastry butter renyah dengan isian tumisan jamur creamy beraroma minyak truffle mewah yang harum gurih menggugah selera.',
                    'note' => 'Sajikan hangat untuk mempertahankan aroma truffle yang kuat dan lezat.',
                ],
                'Original Melon Pan' => [
                    'desc' => 'Roti manis klasik khas Jepang bertekstur lembut di dalam dengan lapisan luar biskuit manis berpola kotak yang renyah renyah wangi.',
                    'note' => 'Masukkan ke dalam kantong roti anti-minyak berlabel Pafu, pastikan permukaan biskuit tidak retak.',
                ],
                'Chocolate Salt Bread' => [
                    'desc' => 'Roti garam khas Jepang dengan tekstur chewy beraroma mentega gurih, taburan garam laut, dan sentuhan lelehan cokelat manis seimbang.',
                    'note' => 'Bungkus satuan dalam kantong roti wax paper higienis.',
                ],
            ],

            'PAK QOMAR' => [
                'Paket Dada Bebek' => [
                    'desc' => 'Paket komplit nasi hangat, dada bebek goreng berbumbu rempah kuning gurih garing, sambal korek pedas mantap, dan lalapan segar.',
                    'note' => 'Bebek digoreng garing saat dipesan. Sambal korek dan bumbu kuning bebek dikemas dalam wadah terpisah agar nasi tidak lembek.',
                ],
                'Paket Paha Bebek' => [
                    'desc' => 'Paket lengkap nasi putih pulen dengan paha bebek empuk berkulit krispi gurih kaya rempah, sambal pedas nampol, dan lalapan timun kol.',
                    'note' => 'Tiriskan minyak dengan sempurna. Bungkus nasi dengan daun pisang di dalam kotak take-away.',
                ],
                'Paket Dada Ayam' => [
                    'desc' => 'Paket nasi ayam bagian dada berdaging tebal gurih berbalut bumbu ungkep tradisional Jawa, disajikan dengan sambal dan lalap.',
                    'note' => 'Pastikan ayam matang sempurna dan panas, sertakan tisu basah dan sendok garpu.',
                ],
                'Paket Paha Ayam' => [
                    'desc' => 'Paket nasi hangat dengan paha ayam goreng renyah gurih bersari bumbu rempah harum, sambal ulek pedas gurih, dan lalapan.',
                    'note' => 'Tata rapi dalam lunch box berbahan kardus tebal tahan panas.',
                ],
                'Bebek Goreng' => [
                    'desc' => 'Potongan bebek goreng bumbu rempah khas Pak Qomar yang gurih meresap hingga ke serat daging terdalam dengan kulit renyah sedap.',
                    'note' => 'Kemas bebek goreng dalam wadah berventilasi udara agar kulit bebek tetap renyah selama perjalanan ke bandara.',
                ],
                'Bebek Sambal Korek' => [
                    'desc' => 'Bebek goreng krispi empuk yang disiram sambal korek cabe rawit pedas berminyak bawang gurih yang meledak di lidah.',
                    'note' => 'Sambal korek ditaruh di wadah kecil tertutup atau disiram sesuai request pembeli.',
                ],
                'Ayam Goreng' => [
                    'desc' => 'Ayam goreng ungkep rempah gurih dengan daging empuk lembut dan aroma bawang ketumbar yang khas menggugah selera.',
                    'note' => 'Sajikan fresh dari penggorengan, taburi remahan bumbu kremes kuning gurih di atasnya.',
                ],
                'Ayam Sambal Korek' => [
                    'desc' => 'Ayam goreng gurih renyah dengan baluran sambal korek ulek kasar yang pedas membakar dan gurih mantap.',
                    'note' => 'Siapkan wadah saus terpisah untuk sambal korek jika penumpang bepergian naik pesawat.',
                ],
                'Nasi Rawon Daging' => [
                    'desc' => 'Nasi rawon khas Jawa Timur dengan kuah kluwek hitam harum, irisan daging sapi empuk, tauge pendek, sambal terasi, dan kerupuk udang.',
                    'note' => 'Kuah rawon dikemas dalam cup sup ber-seal anti bocor. Tauge dan sambal dipisah dalam plastik kecil.',
                ],
                'Nasi Rawon Iga' => [
                    'desc' => 'Sajian istimewa iga sapi berdaging empuk tebal dalam siraman kuah rawon kluwek kaya rempah, lengkap dengan nasi hangat pulen.',
                    'note' => 'Pastikan tulang iga pas di dalam mangkuk sup tanpa menusuk penutup cup. Beri kemasan ganda anti bocor.',
                ],
                'Nasi Soto Daging' => [
                    'desc' => 'Nasi putih dengan kuah soto daging sapi bening kekuningan yang segar beraroma serai dan daun jeruk, lengkap dengan koya gurih.',
                    'note' => 'Kuah soto harus panas mendidih saat di-seal, koya gurih ditaruh di cup kecil terpisah.',
                ],
                'Telur Asin' => [
                    'desc' => 'Telur bebek asin masir berkualitas dengan kuning telur berminyak oranye gurih, pelengkap sempurna rawon dan soto.',
                    'note' => 'Belah dua telur asin secara rapi dan bungkus dengan plastik wrap higienis.',
                ],
                'Tahu Goreng' => [
                    'desc' => 'Tahu kuning lembut yang digoreng garing di luar dengan rasa kedelai gurih alami, cocok sebagai teman makan sambelan.',
                    'note' => 'Tiriskan minyak dengan kertas penyerap, sajikan hangat bersama cabe rawit hijau.',
                ],
                'Es Cendol' => [
                    'desc' => 'Minuman penutup tradisional manis segar berisi cendol pandan kenyal, santan kelapa gurih, sirup gula merah nangka, dan serutan es.',
                    'note' => 'Kemas santan dan gula aren rapat agar tidak basi di suhu ruang kabin. Seal cup dengan mesin press.',
                ],
                'Wedang Ronde' => [
                    'desc' => 'Bola-bola ketan kenyal berisi kacang tumbuk manis gurih dalam kuah jahe hangat beraroma wangi rempah yang menghangatkan tubuh.',
                    'note' => 'Kuah jahe disajikan mendidih, wadah sup wajib menggunakan cup tahan panas bertutup kencang.',
                ],
            ],

            'SHUYI' => [
                'Signature Grass Jelly (L)' => [
                    'desc' => 'Minuman cincau herbal khas Shuyi porsi besar berpadu susu lembut, boba kenyal, kacang merah manis, dan cincau sutra menyegarkan.',
                    'note' => 'Seal cup menggunakan mesin sealer otomatis, sediakan sedotan boba jumbo dan kantong plastik take-away berpegangan.',
                ],
                'Signature Grass Jelly (M)' => [
                    'desc' => 'Menu andalan cincau herbal lembut khas Shuyi berpadu susu manis segar dan aneka topping legit dalam porsi sedang yang pas.',
                    'note' => 'Cek kerapatan lid seal mesin, sertakan sedotan ukuran besar dan tisu.',
                ],
                'Milk Grass Jelly (L)' => [
                    'desc' => 'Perpaduan klasik teh susu harum creamy dengan cincau hitam herbal lembut porsi besar yang sejuk dan melegakan dahaga.',
                    'note' => 'Tanyakan tingkat gula dan es jika ada permintaan khusus, seal rapat anti tumpah.',
                ],
                'Milk Grass Jelly (M)' => [
                    'desc' => 'Teh susu aromatik lembut berpadu potongan cincau hitam sutra kenyal lembut dalam ukuran medium yang pas dinikmati santai.',
                    'note' => 'Kocok lembut sebelum di-seal agar rasa susu dan cincau menyatu sempurna.',
                ],
                'Cheese Lava Boba Grass Jelly' => [
                    'desc' => 'Paduan cincau herbal lembut dan boba manis legit yang disiram lelehan krim keju gurih tebal (cheese foam) yang creamy nagih.',
                    'note' => 'Tuang lapisan cheese foam perlahan di lapisan paling atas, gunakan tutup dome khusus atau pasang seal rapat.',
                ],
                'Brown Sugar Boba Milk' => [
                    'desc' => 'Susu segar dingin gurih berpadu sirup gula aren kental harum dan mutiara boba tapioka kenyal legit yang hangat manis.',
                    'note' => 'Oleskan sirap brown sugar di dinding dalam cup, masukkan boba hangat sebelum dituangi susu segar dan es.',
                ],
                'Matcha Boba Milk' => [
                    'desc' => 'Susu segar berpadu bubuk matcha Jepang berkualitas tinggi dengan aroma earthy khas dan kenyalnya boba manis lembut.',
                    'note' => 'Aduk larutan matcha dengan whisk hingga larut sempurna tanpa gumpalan sebelum dituangkan ke atas susu dan boba.',
                ],
                'Brown Sugar Boba Chocolate' => [
                    'desc' => 'Kombinasi cokelat pekat gurih berpadu sirup gula aren wangi dan kenyalnya boba tapioka yang manis memanjakan lidah.',
                    'note' => 'Pastikan takaran saus cokelat tercampur rata dengan susu, tutup cup dengan lid press anti bocor.',
                ],
                'Grass Jelly Coconut Milk Tea' => [
                    'desc' => 'Milk tea harum dengan sentuhan air kelapa gurih segar dan potongan cincau herbal lembut yang menyejukkan tubuh.',
                    'note' => 'Sajikan dingin dengan takaran es seimbang, pasang seal anti tumpah untuk penumpang pesawat.',
                ],
                'Grass Jelly Cool Cincau Tea (L)' => [
                    'desc' => 'Teh herbal cincau dingin porsi besar dengan rasa manis ringan menyegarkan yang efektif meredakan panas dalam saat perjalanan.',
                    'note' => 'Pastikan es batu bersih dan cup tersegel kencang. Sediakan sedotan ramah lingkungan.',
                ],
                'Grass Jelly Cool Cincau Tea (M)' => [
                    'desc' => 'Minuman teh cincau segar ukuran sedang dengan ekstrak herbal alami yang manis sejuk dan melegakan tenggorokan.',
                    'note' => 'Sajikan dingin, seal rapat dengan cup sealer.',
                ],
                'Peach Oolong Milk Tea' => [
                    'desc' => 'Teh oolong wangi berpadu sari buah persik harum manis dan susu creamy lembut dengan aroma buah yang elegan.',
                    'note' => 'Shake rata dengan shaker cocktail sebelum dituangkan ke cup kemasan take-away.',
                ],
                'Coconut Peach Milk Tea' => [
                    'desc' => 'Kesegaran kelapa tropis berpadu aroma persik manis harum dan kelembutan teh susu aromatik yang unik dan nikmat.',
                    'note' => 'Kocok merata dengan es, pastikan tidak ada kebocoran pada tutup cup.',
                ],
                'Roast Oolong Milk Tea' => [
                    'desc' => 'Teh oolong panggang dengan aroma woody smoky yang khas dan mendalam, berpadu susu creamy gurih berkelas.',
                    'note' => 'Tuang teh oolong yang sudah diseduh fresh, sesuaikan takaran manis dan beri segel rapi.',
                ],
            ],

            'GLORIA JEAN\'S COFFEES' => [
                'Caramel Latte' => [
                    'desc' => 'Paduan espresso arabika premium, susu steamed lembut creamy, dan sirup karamel legit manis beraroma wangi mentega panggang.',
                    'note' => 'Tuang saus karamel di dinding cup, tutup dengan lid anti tumpah khusus take-away kabin bandara.',
                ],
                'Caffè Latte' => [
                    'desc' => 'Kopi klasik perpaduan double shot espresso pilihan Gloria Jeans dengan susu segar steamed bertekstur halus dan rasa kopi yang lembut seimbang.',
                    'note' => 'Sajikan panas dalam cup insulated dengan pelindung panas sleeve karton.',
                ],
                'Cappuccino' => [
                    'desc' => 'Kopi Italia klasik dengan perpaduan espresso mantap, susu panas, dan lapisan foam tebal bertekstur sutra bertabur bubuk cokelat.',
                    'note' => 'Taburi cokelat bubuk tipis di atas lapisan microfoam tebal sebelum ditutup lid kopi.',
                ],
                'Hot Caramel Latte' => [
                    'desc' => 'Minuman hangat kopi espresso kaya rasa berpadu susu panas berbusa lembut dan sirup karamel emas yang manis harum menenangkan.',
                    'note' => 'Pastikan suhu minuman panas stabil di 68°C, pasang lid penutup dan sleeve tahan panas.',
                ],
                'Hot Very Vanilla Latte' => [
                    'desc' => 'Kopi latte hangat istimewa dengan ekstrak vanila Madagaskar yang manis harum berpadu espresso mantap dan susu lembut.',
                    'note' => 'Larutkan bubuk vanila signature Gloria Jeans hingga larut sempurna dalam espresso panas sebelum menuang susu.',
                ],
                'Hot Mocha Caramel Latte' => [
                    'desc' => 'Kombinasi hangat nan mewah antara cokelat kakao pekat, saus karamel legit, espresso kuat, dan steamed milk berbusa lembut.',
                    'note' => 'Aduk saus cokelat dan karamel di dasar cup bersama espresso panas sebelum susu dituangkan.',
                ],
                'Very Vanilla Chiller' => [
                    'desc' => 'Minuman es blender (frappe) rasa vanila creamy khas Gloria Jeans berpadu susu dan es batu halus bertabur whipped cream tebal lembut.',
                    'note' => 'Semprotkan whipped cream swirl di atas frappe, tutup dengan dome lid dan sediakan sedotan ukuran besar.',
                ],
                'Crème Brûlée Chiller' => [
                    'desc' => 'Ice blended mewah bercita rasa hidangan penutup crème brûlée Prancis, berpadu sirup karamel gosong manis legit dan whipped cream.',
                    'note' => 'Blender es hingga bertekstur halus mulus, beri drizzle saus karamel di atas whipped cream.',
                ],
                'Cookies \'N Cream Chiller' => [
                    'desc' => 'Minuman es frappe blender bertekstur krispi berpadu remahan biskuit cokelat oreo hitam pekat, susu creamy, dan topping whipped cream manis.',
                    'note' => 'Masukkan remahan biskuit pada putaran blender terakhir agar tetap menyisakan tekstur crunchy saat disedot.',
                ],
                'Iced Mocha' => [
                    'desc' => 'Paduan espresso dingin segar berpadu saus cokelat pekat, susu segar dingin, dan es batu segar pelepas dahaga.',
                    'note' => 'Pastikan cokelat larut merata bersama espresso dan es, gunakan cup take-away bersegel rapat.',
                ],
                'Iced Matcha Latte' => [
                    'desc' => 'Minuman es teh hijau matcha asli berpadu susu segar creamy dingin dengan rasa manis seimbang yang menyegarkan tubuh.',
                    'note' => 'Whisk matcha dingin dengan milk frother hingga busa halus terbentuk sebelum dituang ke dalam cup berisi es.',
                ],
                'Cocoa Loco Chiller' => [
                    'desc' => 'Frappe es blender cokelat pekat ganda super kaya rasa dengan lelehan saus cokelat hitam pekat dan whipped cream melimpah.',
                    'note' => 'Lapisi bagian dalam dinding cup dengan saus cokelat pekat sebelum menuang chiller es.',
                ],
                'Club Sandwich' => [
                    'desc' => 'Roti panggang tiga lapis bertumpuk potongan daging ayam panggang asap, telur mata sapi, selada segar, tomat, dan saus mayones gurih.',
                    'note' => 'Potong diagonal menjadi 4 segitiga, tancapkan tusuk kayu sandwich dan kemas dalam box bersekat.',
                ],
                'GJC\'s Burger' => [
                    'desc' => 'Roti burger brioche bermentega lembut dengan patty daging sapi panggang juicy tebal, keju cheddar leleh, selada segar, dan saus burger spesial.',
                    'note' => 'Bungkus burger dengan kertas burger anti minyak lalu masukkan ke dalam box burger karton tebal.',
                ],
                'Big Breakfast' => [
                    'desc' => 'Paket sarapan komplit ala Barat berisi sosis sapi panggang lezat, telur orak-arik lembut (scrambled egg), kentang hash brown renyah, jamur tumis, dan roti panggang.',
                    'note' => 'Tata seluruh komponen dengan rapi dalam box bento sekat 4, sertakan mentega sachet, selai, dan alat makan lengkap.',
                ],
            ],
        ];

        // Jalankan pembaruan deskripsi produk untuk seluruh tenant di Terminal 1
        $tenants = DB::table('tenants')->where('terminal', 'T1')->get();

        foreach ($tenants as $tenant) {
            $brand = strtoupper(trim($tenant->name));
            if (isset($catalog[$brand])) {
                foreach ($catalog[$brand] as $productName => $data) {
                    DB::table('products')
                        ->where('tenant_id', $tenant->id)
                        ->where('name', $productName)
                        ->update([
                            'description' => $data['desc'],
                            'note'        => $data['note'],
                        ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tenantIds = DB::table('tenants')->where('terminal', 'T1')->pluck('id');

        DB::table('products')
            ->whereIn('tenant_id', $tenantIds)
            ->update([
                'description' => null,
                'note'        => null,
            ]);
    }
};
