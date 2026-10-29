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

            'OLD TOWN' => [
                'OLDTOWN White Coffee' => [
                    'desc' => 'Kopi putih legendaris khas Ipoh yang disangrai dengan mentega khusus, menghasilkan aroma karamel legit dan rasa creamy seimbang.',
                    'note' => 'Tutup cup dengan lid anti bocor, pasang sleeve karton penahan panas jika panas, atau seal film jika dingin.',
                ],
                'OLDTOWN White Coffee Mocha' => [
                    'desc' => 'Paduan rasa kopi putih khas Old Town dengan cokelat pekat lembut yang menghadirkan kenikmatan manis beraroma harum.',
                    'note' => 'Aduk merata sebelum di-seal, pastikan es batu tidak melebihi batas cup.',
                ],
                'OLDTOWN White Coffee Hazelnut' => [
                    'desc' => 'White coffee otentik dengan sentuhan aroma hazelnut gurih yang manis nutty, pas menemani waktu santai di bandara.',
                    'note' => 'Berikan sedotan kertas ramah lingkungan dan pasang lid plug anti-tumpah.',
                ],
                'OLDTOWN White Coffee Gao' => [
                    'desc' => 'Varian white coffee ekstra pekat dan mantap untuk pecinta kopi berkarakter tebal dengan tendangan kafein prima sebelum penerbangan.',
                    'note' => 'Pastikan takaran espresso dan krimer kental pas sesuai standar Gao yang bold.',
                ],
                'Cappuccino' => [
                    'desc' => 'Perpaduan seimbang antara espresso robusta-arabika mantap, susu steamed lembut, dan lapisan microfoam tebal bertabur bubuk cokelat.',
                    'note' => 'Tuang microfoam secara halus, taburi bubuk cokelat tipis di atasnya sebelum dipasang lid pelindung.',
                ],
                'Coffee Latte' => [
                    'desc' => 'Kopi espresso klasik berpadu susu segar hangat bertekstur lembut dan rasa kopi yang lembut bersahabat.',
                    'note' => 'Sajikan dalam suhu 65°C, gunakan cup take-away insulasi ganda anti-panas.',
                ],
                'Teh Tarik' => [
                    'desc' => 'Teh hitam aromatik yang ditarik secara terampil hingga menghasilkan busa lembut melimpah berpadu susu kental manis legit.',
                    'note' => 'Tarik teh hingga berbusa padat sebelum dituangkan ke cup take-away.',
                ],
                'Fresh Lemon Tea' => [
                    'desc' => 'Teh hitam segar dingin berpadu perasan jeruk lemon asli yang asam manis menyegarkan tenggorokan setelah seharian beraktivitas.',
                    'note' => 'Berikan irisan lemon segar di dalam cup, pastikan es batu cukup dan cup tersegel rapi.',
                ],
                'Nasi Lemak Rendang Chicken' => [
                    'desc' => 'Nasi lemak harum santan dan daun pandan disajikan dengan rendang ayam empuk kaya rempah, sambal bilis gurih, kacang, dan telur.',
                    'note' => 'Sambal bilis dan kacang teri dipisah atau ditaruh di kompartemen terpisah agar tetap renyah saat dibawa.',
                ],
                'Nasi Lemak With Fried Chicken' => [
                    'desc' => 'Nasi gurih santan harum lengkap dengan paha ayam goreng renyah berempah bumbu khas melayu, sambal terasi manis pedas, dan telur.',
                    'note' => 'Ayam goreng harus ditiriskan minyaknya dan disajikan hangat. Kemas dalam bento box bertingkat.',
                ],
                'Nasi Lemak Whole Leg' => [
                    'desc' => 'Porsi jumbo mengenyangkan nasi lemak wangi pandan dengan satu potong paha ayam utuh renyah gurih, sambal, telur rebus, dan timun.',
                    'note' => 'Gunakan bento box ukuran besar, pastikan ayam utuh tertata kokoh dan tidak menekan lauk pendamping.',
                ],
                'Kaya & Butter Toast (Double)' => [
                    'desc' => 'Roti panggang garing renyah khas kopitiam dengan selai srikaya manis harum dan potongan butter dingin yang lumer di mulut.',
                    'note' => 'Potong roti menjadi 4 bagian rapi, bungkus dengan kertas roti pembungkus panas agar kehangatan terjaga.',
                ],
                'Peanut Butter Toast (Double)' => [
                    'desc' => 'Roti bakar renyah garing dengan olesan selai kacang creamy yang tebal, gurih, dan legit di setiap gigitan.',
                    'note' => 'Panggang roti hingga cokelat keemasan, oleskan selai kacang merata saat roti masih panas.',
                ],
                'Tuna Toast' => [
                    'desc' => 'Roti panggang renyah berisikan olahan daging ikan tuna gurih berpadu mayonnaise lembut dan irisan sayuran segar.',
                    'note' => 'Kemas rapat dalam sandwich box segitiga anti minyak, sediakan tisu makan ekstra.',
                ],
                'French Fries Basket' => [
                    'desc' => 'Kentang goreng potongan lurus renyah keemasan bertabur garam gurih renyah, pas untuk camilan santai sebelum boarding.',
                    'note' => 'Goreng fresh 3 menit, jangan tutup kantong kertas terlalu rapat agar uap keluar dan kentang tidak lembek.',
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

            'KULINER SANDANG PANGAN' => [
                'Bakso Campur Pak Djo' => [
                    'desc' => 'Semangkuk bakso campur khas Jawa Timuran lengkap dengan bakso halus, bakso urat, tahu bakso, siomay basah, dan kuah kaldu sapi gurih pekat.',
                    'note' => 'Pisahkan kuah kaldu dalam wadah mangkuk anti panas berpenutup rapat. Sertakan sambal cabe rawit dan jeruk nipis.',
                ],
                'Nasi Goreng Ayam' => [
                    'desc' => 'Nasi goreng tradisional bumbu ulek gurih harum dengan suwiran ayam, potongan telur, dan taburan bawang merah goreng renyah.',
                    'note' => 'Kemas dalam box ramah lingkungan, lengkapi dengan acar mentimun dan kerupuk renyah.',
                ],
                'Sambelan Ayam Goreng' => [
                    'desc' => 'Paket nasi hangat dengan ayam goreng bumbu kuning gurih garing berpadu sambal terasi ulek pedas mantap dan lalapan segar.',
                    'note' => 'Ayam goreng disajikan panas. Sambal ulek ditaruh di cup sambal bersegel agar tidak mengotori kemasan box.',
                ],
                'Sambelan Empal Goreng' => [
                    'desc' => 'Nasi putih pulen dengan empal daging sapi manis gurih empuk yang kaya rempah ketumbar, disajikan bersama sambal pedas pedas gurih.',
                    'note' => 'Empal dipotong siap makan, sertakan lalapan timun dan kemangi bersih.',
                ],
                'Sambelan Ayam Bakar' => [
                    'desc' => 'Ayam bakar bumbu kecap rempah dengan aroma smokey manis gurih meresap sampai ke tulang, disajikan dengan sambal dan nasi hangat.',
                    'note' => 'Olesi bumbu kecap manis sebelum dikemas, bungkus ayam dengan alas daun pisang di dalam kotak bento.',
                ],
                'Sambelan Makmur Makan Murah' => [
                    'desc' => 'Menu hemat berselera berisi nasi hangat, telur dadar tebal gurih, tahu tempe goreng, dan sambel ulek pedas nikmat khas bandara.',
                    'note' => 'Sajikan telur dan tahu tempe dalam kondisi hangat fresh, sediakan sendok garpu higienis.',
                ],
                'Soto Campur Istimewa' => [
                    'desc' => 'Soto khas Jawa Timur dengan kuah kuning rempah kunyit harum bertabur koyah gurih, berisi irisan daging sapi empuk dan jeroan sedap.',
                    'note' => 'Kuah soto WAJIB dipisah dalam kantong/mangkuk sup ber-seal rapat. Koya gurih dan sambal dibungkus terpisah.',
                ],
                'Soto Daging' => [
                    'desc' => 'Kuah soto kuning bening segar beraroma serai dan daun jeruk dengan potongan daging sapi empuk gurih dan taburan koya renyah.',
                    'note' => 'Pastikan kuah mendidih saat dimasukkan ke cup sup tahan panas. Berikan potongan jeruk nipis segar.',
                ],
                'Soto Ayam' => [
                    'desc' => 'Soto ayam kuah kuning gurih beraroma rempah tradisional dengan suwiran ayam melimpah, soun lembut, dan koya gurih nagih.',
                    'note' => 'Kuah dan soun dipisah agar soun tidak mengembang selama perjalanan penumpang di bandara.',
                ],
                'Rawon Sapi' => [
                    'desc' => 'Sup daging sapi khas Jawa Timur berkuah hitam pekat dari kluwek alami pilihan, gurih pekat dengan aroma rempah yang otentik.',
                    'note' => 'Kemas kuah rawon rapat tanpa tumpah, sertakan tauge pendek segar, sambal terasi, dan potongan telur asin.',
                ],
                'Sop Buntut' => [
                    'desc' => 'Buntut sapi impor empuk berdaging tebal dalam kuah kaldu bening kaya rempah kapulaga, pala, wortel, dan kentang empuk.',
                    'note' => 'Gunakan wadah mangkok sup bervolume besar bertutup ulir/seal ganda. Sertakan sambal rawon/sop dan emping melinjo.',
                ],
                'Lontong Kupang' => [
                    'desc' => 'Makanan khas pesisir Sidoarjo berupa kupang (kerang kecil) empuk gurih berpadu bumbu petis manis sedap, lontong, dan lentho singkong renyah.',
                    'note' => 'Kemas kuah petis kupang dengan teliti agar aroma tetap terjaga dan tidak bocor ke tas kabin penumpang.',
                ],
                'Siomay Campur' => [
                    'desc' => 'Olahan siomay ikan tenggiri gurih kenyal berpadu tahu putih, kentang rebus, kubis, dan siraman bumbu kacang gurih manis kental.',
                    'note' => 'Bumbu kacang disiram merata atau dipisah sesuai permintaan pelanggan. Berikan kecap manis dan perasan jeruk limau.',
                ],
                'Siomay Lengkap' => [
                    'desc' => 'Porsi lengkap siomay tenggiri asli dengan tambahan telur rebus utuh, pare pahit gurih, tahu bakso, dan saus kacang medok beraroma sedap.',
                    'note' => 'Potong-potong siomay rapi dalam mangkuk take-away, letakkan telur rebus utuh di bagian tengah.',
                ],
                'Pisang Coklat' => [
                    'desc' => 'Camilan pisang raja manis lumer berbalut kulit lumpia renyah garing bertabur cokelat lezat yang manis menggoda.',
                    'note' => 'Tiriskan minyak dengan kertas serap minyak sebelum dimasukkan ke kantong kertas anti minyak.',
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

            'ROTI\'O' => [
                'Roti\'O Original Coffee Bun' => [
                    'desc' => 'Roti bun hangat berlapis krim kopi aromatik, renyah di luar dengan butter meleleh di dalam.',
                    'note' => 'Sajikan fresh dari warmer box, kemas rapat dalam kantong anti minyak untuk take-away.',
                ],
                'Roti\'O Cheese Cream' => [
                    'desc' => 'Roti lembut bertabur keju parut gurih berlimpah dengan lelehan krim keju manis legit khas Roti\'O.',
                    'note' => 'Pastikan kemasan rapi dan tidak tertindih roti bun hangat.',
                ],
                'Roti\'O Chocolate' => [
                    'desc' => 'Roti lembut dengan lelehan cokelat premium pekat manis gurih yang lumer di setiap gigitan.',
                    'note' => 'Hangatkan 30 detik jika penumpang memesan hangat.',
                ],
                'Roti\'O Banana Choco Cheese' => [
                    'desc' => 'Kombinasi pisang manis harum, cokelat lumer, dan keju gurih dalam balutan roti lembut khas Roti\'O.',
                    'note' => 'Kemas menggunakan box take-away khusus agar bentuk tetap utuh.',
                ],
                'Roti\'O Crombo\'O Chocolate' => [
                    'desc' => 'Pastry renyah berlapis-lapis khas cromboloni dengan lelehan krim cokelat manis gurih melimpah.',
                    'note' => 'Gunakan jepitan pastry saat memasukkan ke kotak take-away.',
                ],
                'Roti\'O Almond Turnover' => [
                    'desc' => 'Pastry segitiga renyah beraroma mentega dengan taburan irisan almond panggang dan isian manis gurih.',
                    'note' => 'Sajikan hangat untuk kerenyahan maksimal.',
                ],
                'Roti\'O Tuna Pastry' => [
                    'desc' => 'Puff pastry gurih renyah dengan isian daging ikan tuna berbumbu rempah lezat dan gurih.',
                    'note' => 'Tawarkan saus sambal sachet saat penyerahan pesanan.',
                ],
                'Roti\'O Beef Pastry' => [
                    'desc' => 'Puff pastry emas berlapis mentega dengan isian daging sapi cincang berbumbu lada hitam gurih.',
                    'note' => 'Hangatkan sebentar sebelum diserahkan ke pelanggan.',
                ],
                'Roti\'O Chicken Pastry' => [
                    'desc' => 'Pastry renyah dengan isian daging ayam cincang gurih berbumbu sedap yang cocok untuk teman perjalanan.',
                    'note' => 'Sajikan hangat dengan tisu dan kantong bersih.',
                ],
                'Roti\'O Cheese Feuillette' => [
                    'desc' => 'Pastry tipis berlapis keju renyah garing dengan rasa gurih mentega yang nagih.',
                    'note' => 'Pastikan kemasan tidak tergencet.',
                ],
                'Butter Croissant' => [
                    'desc' => 'Croissant klasik Perancis dengan lapisan mentega gurih keemasan dan tekstur berongga lembut di dalam.',
                    'note' => 'Panggang 1 menit jika diminta hangat oleh penumpang.',
                ],
                'Sausage Croissant' => [
                    'desc' => 'Pastry croissant renyah berselubung mentega dengan isian sosis sapi premium gurih lezat.',
                    'note' => 'Sajikan hangat, berikan saus sambal sachet.',
                ],
                'Kopi\'O Aren' => [
                    'desc' => 'Espresso robusta dengan gula aren murni dan susu krim lembut.',
                    'note' => 'Tutup cup rapat dan pasang lid seal anti-tumpah untuk penumpang kabin/transit.',
                ],
                'Kopi\'O Latte' => [
                    'desc' => 'Paduan seimbang antara double shot espresso dan susu steamed lembut dengan rasa kopi yang creamy.',
                    'note' => 'Tutup cup rapat dan gunakan sleeve pelindung panas jika dipesan hangat.',
                ],
                'Chocolate Drink' => [
                    'desc' => 'Minuman cokelat pekat nikmat dari biji kakao pilihan dengan rasa creamy manis gurih yang menyegarkan.',
                    'note' => 'Aduk rata sebelum disajikan agar cokelat larut sempurna.',
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

            'MARUGAME UDON' => [
                'Niku Udon' => [
                    'desc' => 'Mie udon khas Jepang tebal lembut kenyal dalam kuah kake dashi hangat gurih, disajikan dengan irisan daging sapi sukiyaki manis empuk.',
                    'note' => 'Mie udon dan kuah dashi WAJIB dipisah dalam wadah take-away bertingkat (two-tier bowl) agar mie tidak overcooked.',
                ],
                'Beef Curry Udon' => [
                    'desc' => 'Udon kenyal tebal disiram kuah kari Jepang kental gurih beraroma rempah harum dengan topping irisan daging sapi empuk.',
                    'note' => 'Kuah kari kental ditaruh di wadah terpisah atau di kompartemen atas bowl take-away Marugame.',
                ],
                'Beef Abura Udon' => [
                    'desc' => 'Udon kering kenyal berbalut saus abura gurih minyak wijen sedap, disajikan dengan daging sapi sukiyaki empuk dan taburan remah tempura.',
                    'note' => 'Berikan saus abura merata pada mie, masukkan remah tempura (tenkasu) dan daun bawang dalam wadah kecil terpisah agar tetap garing.',
                ],
                'Beef Carbonara Udon' => [
                    'desc' => 'Perpaduan udon Jepang kenyal dengan saus krim carbonara gurih lembut, irisan daging sapi, keju cheddar parut, dan kuning telur setengah matang.',
                    'note' => 'Pastikan saus carbonara panas saat disiramkan, bungkus rapi dengan wrap anti tumpah.',
                ],
                'Tori Baitan Udon' => [
                    'desc' => 'Udon tebal lembut dalam kuah kaldu ayam putih kental gurih yang kaya rasa (paitan), disajikan dengan bakso ayam panggang empuk.',
                    'note' => 'Kuah paitan kaldu ayam disajikan mendidih dalam wadah sup tertutup rapat.',
                ],
                'Teriyaki Abura Udon' => [
                    'desc' => 'Udon tanpa kuah dengan saus abura gurih manis, potongan ayam paha panggang berlumur saus teriyaki gurih, dan daun bawang segar.',
                    'note' => 'Ayam teriyaki dipotong rapi, kemas dalam bento box take-away bersegel.',
                ],
                'Chicken Katsu Curry Udon' => [
                    'desc' => 'Udon tebal lembut berpadu kuah kari Jepang kental harum lengkap dengan potongan chicken katsu goreng renyah keemasan.',
                    'note' => 'Chicken katsu diletakkan di kompartemen atas atau dibungkus terpisah agar tidak terendam kuah kari dan tetap krispi.',
                ],
                'Spicy Tori Baitan Udon' => [
                    'desc' => 'Kuah kaldu ayam paitan putih kental dengan sentuhan minyak cabai pedas gurih, mie udon kenyal, dan daging ayam cincang pedas lezat.',
                    'note' => 'Kuah kaldu pedas dikemas terpisah, sediakan potongan cabai rawit potong dan daun bawang.',
                ],
                'Kake Udon' => [
                    'desc' => 'Sajian udon klasik autentik khas Jepang dengan kuah dashi ikan cakalang dan rumput laut bening gurih hangat yang menenangkan.',
                    'note' => 'Kuah dashi bening dipisahkan dalam mangkuk sup bawah. Sediakan remah krispi tenkasu di plastik terpisah.',
                ],
                'Sukiyaki Beef Rice' => [
                    'desc' => 'Nasi putih pulen Jepang hangat dengan topping irisan tipis daging sapi sukiyaki lembut dan bawang bombay berkuah manis gurih sedap.',
                    'note' => 'Tata daging sukiyaki di atas nasi pulen, tutup box donburi rapat dan sertakan sendok garpu.',
                ],
                'Beef Curry Rice' => [
                    'desc' => 'Nasi putih hangat disiram saus kari Jepang gurih kental dengan potongan daging sapi empuk, wortel, dan kentang lembut.',
                    'note' => 'Saus kari kental ditaruh di wadah atas atau siram merata sesuai permintaan, pastikan tutup box rapat.',
                ],
                'Chicken Katsu Curry Rice' => [
                    'desc' => 'Nasi putih pulen hangat dengan potongan chicken katsu dada ayam fillet renyah dan siraman kuah kari khas Jepang yang gurih harum.',
                    'note' => 'Pisahkan potongan chicken katsu dari kuah kari di dalam box bersekat agar tepung katsu tetap krispi garing.',
                ],
                'Tendon Rice' => [
                    'desc' => 'Nasi pulen Jepang hangat dengan aneka tempura renyah keemasan (udang ebi, sayuran) disiram saus tare manis gurih yang sedap.',
                    'note' => 'Jangan tutup box terlalu rapat selagi uap tempura masih panas agar tempura tidak lembek.',
                ],
                'Teriyaki Chicken Rice' => [
                    'desc' => 'Nasi putih hangat dengan potongan paha ayam empuk berbalut saus teriyaki manis gurih mengkilap dan taburan wijen sangrai wangi.',
                    'note' => 'Oleskan saus teriyaki ekstra di atas ayam sebelum dikemas, sertakan acar jahe gari jika diminta.',
                ],
                'Spicy Tori Rice' => [
                    'desc' => 'Nasi putih pulen dengan suwiran ayam cincang berbumbu pedas gurih khas Jepang dan taburan daun bawang serta nori renyah.',
                    'note' => 'Kemas dalam mangkuk bowl tahan panas, sertakan sendok dan tisu.',
                ],
            ],

            'PADANG MERDEKA' => [
                'Rendang Sapi Merdeka' => [
                    'desc' => 'Daging sapi pilihan empuk yang dimasak berjam-jam dengan santan kental kelapa asli dan rempah Minang otentik hingga berwarna hitam kecokelatan gurih legit.',
                    'note' => 'Kemas daging rendang bersama lumuran bumbu dedak rendang tebal dalam wadah bersegel plastik rapat agar minyak tidak rembes.',
                ],
                'Dendeng Balado' => [
                    'desc' => 'Irisan tipis daging sapi goreng garing renyah bertabur sambal balado merah cabai ulek kasar yang pedas gurih segar beraroma jeruk nipis.',
                    'note' => 'Kemas dendeng garing dan sambal balado dalam wadah terpisah agar kerenyahan daging tetap terjaga.',
                ],
                'Dendeng Lambok' => [
                    'desc' => 'Daging sapi pipih empuk bertekstur basah lembut yang dibumbui rempah ketumbar dan dilumuri sambal cabai merah asam pedas nikmat.',
                    'note' => 'Siramkan sambal lambok merata di atas daging, kemas dalam kotak bento bersekat anti bocor.',
                ],
                'Gulai Cincang Sapi' => [
                    'desc' => 'Potongan daging sapi dan tetelan empuk dalam kuah gulai santan kental kemerahan kaya rempah kapulaga, cengkeh, dan kayu manis yang harum semerbak.',
                    'note' => 'Kuah gulai kental dikemas dalam mangkuk sup bertutup rapat dengan seal pelindung anti rembes.',
                ],
                'Gulai Otak' => [
                    'desc' => 'Otak sapi lembut lumer dalam kuah gulai santan kuning gurih beraroma daun kunyit dan serai khas Ranah Minang yang melegenda.',
                    'note' => 'Tangani potongan otak dengan hati-hati saat dikemas agar tekstur lembutnya tidak hancur.',
                ],
                'Ayam Bakar' => [
                    'desc' => 'Potongan ayam ungkep bumbu gulai yang dibakar harum di atas arang dengan olesan bumbu santan pekat manis gurih beraroma asap sedap.',
                    'note' => 'Bungkus ayam bakar dengan daun pisang sebelum dimasukkan ke dalam kotak bento nasi padang.',
                ],
                'Ayam Balado' => [
                    'desc' => 'Ayam goreng gurih renyah yang dilumuri tumisan sambal balado cabai merah ulek segar beraroma bawang merah wangi dan pedas menggugah selera.',
                    'note' => 'Kemas ayam balado dalam keadaan hangat, pastikan minyak sambal tidak merembes keluar kemasan.',
                ],
                'Ayam Cabe Ijo' => [
                    'desc' => 'Ayam ungkep gurih berpadu siraman sambal lada mudo (cabai hijau keriting) ulek kasar yang gurih segar beraroma daun jeruk.',
                    'note' => 'Pastikan sambal hijau menutupi permukaan ayam, sertakan lalap daun singkong rebus.',
                ],
                'Ayam Pop' => [
                    'desc' => 'Ayam kampung pejantan muda lembut yang dimasak dengan air kelapa dan bawang putih gurih tanpa kulit, disajikan bersama saus tomat cabai khas ayam pop.',
                    'note' => 'Saus cabai cocolan ayam pop dikemas dalam cup saus bersegel terpisah.',
                ],
                'Ayam Rendang' => [
                    'desc' => 'Potongan ayam empuk yang dimasak perlahan dalam kuah bumbu rendang Minang kaya rempah harum hingga bumbunya meresap pekat sampai ke serat.',
                    'note' => 'Lumuri bumbu rendang melimpah di atas potongan ayam, kemas rapi dalam wadah take-away.',
                ],
                'Cumi Asin Balado' => [
                    'desc' => 'Cumi asin kenyal gurih yang ditumis harum bersama sambal balado cabai merah pedas manis dan irisan tomat segar.',
                    'note' => 'Pastikan cumi asin tidak terlalu basah dan minyak sambal tiris aman di dalam wadah makan.',
                ],
                'Ikan Kakap Balado' => [
                    'desc' => 'Potongan ikan kakap segar goreng gurih dengan siraman sambal balado merah pedas gurih yang meresap sedap ke daging ikannya.',
                    'note' => 'Kemas ikan kakap dalam wadah datar agar daging ikan tidak patah saat dibawa transit.',
                ],
                'Nasi Rames' => [
                    'desc' => 'Porsi komplit nasi putih pulen harum khas Padang lengkap dengan siraman kuah gulai, sayur nangka muda kapau, daun singkong rebus, dan sambal hijau.',
                    'note' => 'Siraman aneka kuah gulai dapat dibungkus terpisah jika pelanggan ingin menikmati saat penerbangan nanti.',
                ],
                'Telur Dadar' => [
                    'desc' => 'Telur dadar tebal padat khas Minang bertabur irisan daun bawang, cabai merah, dan kelapa sangrai gurih dengan pinggiran garing krispi.',
                    'note' => 'Potong telur dadar menjadi potongan segitiga rapi, letakkan di wadah kering.',
                ],
                'Sambal Kentang Ati' => [
                    'desc' => 'Potongan dadu kentang goreng dan hati sapi empuk gurih yang dimasak dengan bumbu balado sambal merah harum manis pedas.',
                    'note' => 'Kemas dalam wadah tertutup rapat, cocok sebagai lauk pendamping nasi padang.',
                ],
            ],

            'GLORIA JEANS' => [
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

        $tenants = DB::table('tenants')->where('terminal', 'T2')->get();

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
        $tenantIds = DB::table('tenants')->where('terminal', 'T2')->pluck('id');

        DB::table('products')
            ->whereIn('tenant_id', $tenantIds)
            ->update([
                'description' => null,
                'note'        => null,
            ]);
    }
};

