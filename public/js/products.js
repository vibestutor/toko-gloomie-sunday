const allProducts = [
    {
        id: 'crewneck-breaker-1',
        brand: 'GLOOMIE SUNDAY',
        name: 'CREWNECK - CREWNECK BREAKER',
        price: 199000,
        image: 'img/produk/CREWNECK BREAKER.jpg',
        thumbnails: [
            'img/produk/CREWNECK BREAKER.jpg',
            'img/produk/CREWNECK BREAKER DETAIL.jpg',
            'img/size chart/size chart crewneck breaker.jpg'
        ],
        description: 'Gloomie Sunday Original Indonesian Apparel. Fitting: Boxy Fit (Unisex). Material: Cotton Fleece (80% Cotton, 20% Polyester).'
    },
    {
        id: 'sweatpants-breaker-1',
        brand: 'GLOOMIE SUNDAY',
        name: 'SWEATPANTS - SWEATPANTS BREAKER',
        price: 199000,
        image: 'img/produk/SP BREAKER.jpg',
        thumbnails: [
            'img/produk/SP BREAKER.jpg',
            'img/produk/SP BREAKER B.jpg'
        ],
        description: 'Sweatpants nyaman dengan desain modern untuk tampilan kasual.'
    },
    {
        id: 'jersey-skoolyard-1',
        brand: 'GLOOMIE SUNDAY',
        name: 'JERSEY - SKOOLYARD JERSEY',
        price: 139000,
        image: 'img/produk/JERSEY.jpg',
        thumbnails: [
            'img/produk/JERSEY.jpg',
            'img/produk/JERSEY B.jpg'
        ],
        description: 'Jersey olahraga dengan bahan berkualitas tinggi, nyaman untuk aktivitas sehari-hari.'
    },
    {
        id: 'bw-cherry',
        brand: 'GLOOMIE SUNDAY',
        name: 'HOODIE BOXY PRINTING - YOUR EVERYTHING',
        price: 199000,
        image: 'img/produk/CHERRY.jpg',
        thumbnails: [
            'img/produk/CHERRY.jpg',
            'img/produk/CHERRY B.jpg',
            'img/size chart/size chart crewneck breaker.jpg'
        ],
        description: 'Gloomie Sunday Original Indonesian Apparel. Fitting: Boxy Fit (Unisex). Material: Cotton Fleece (80% Cotton, 20% Polyester).'
    },
    {
        id: 'boxy-pendek-hitam',
        brand: 'GLOOMIE SUNDAY',
        name: 'HOODIE SHORT SLEEVE - BLACK',
        price: 139000,
        image: 'img/produk/BOXY PENDEK.jpg',
        thumbnails: [
            'img/produk/BOXY PENDEK.jpg',
            'img/produk/BOXY PENDEK B.jpg'
        ],
        description: 'Sweatpants nyaman dengan desain modern untuk tampilan kasual.'
    },
    {
        id: 'boxy-farewell',
        brand: 'GLOOMIE SUNDAY',
        name: 'TSHIRT BOXY PRINTING - FAREWELL',
        price: 79000,
        image: 'img/produk/FAREWELL.jpg',
        thumbnails: [
            'img/produk/FAREWELL.jpg',
            'img/produk/FAREWELL B.jpg'
        ],
        description: 'Jersey olahraga dengan bahan berkualitas tinggi, nyaman untuk aktivitas sehari-hari.'
    },
    {
        id: 'kaos-boxy-putih',
        brand: 'GLOOMIE SUNDAY',
        name: 'TSHIRT BOXY - TSHIRT WHITE',
        price: 64000,
        image: 'img/produk/KAOS BOXY.jpg',
        thumbnails: [
            'img/produk/KAOS BOXY.jpg',
            'img/produk/KAOS BOXY B.jpg',
            'img/size chart/size chart crewneck breaker.jpg'
        ],
        description: 'Gloomie Sunday Original Indonesian Apparel. Fitting: Boxy Fit (Unisex). Material: Cotton Fleece (80% Cotton, 20% Polyester).'
    },
    {
        id: 'kupluk',
        brand: 'GLOOMIE SUNDAY',
        name: 'KUPLUK BENNIE',
        price: 20500,
        image: 'img/produk/KUPLUK.jpg',
        thumbnails: [
            'img/produk/KUPLUK.jpg',
            'img/produk/KUPLUK 1.jpg'
        ],
        description: 'Sweatpants nyaman dengan desain modern untuk tampilan kasual.'
    },
    {
        id: 'boxy-make-love',
        brand: 'GLOOMIE SUNDAY',
        name: 'HOODIE BOXY REFLECTIVE - MAKE LOVE',
        price: 229000,
        image: 'img/produk/MAKE LOVE.jpg',
        thumbnails: [
            'img/produk/MAKE LOVE.jpg',
            'img/produk/MAKE LOVE B.jpg'
        ],
        description: 'Jersey olahraga dengan bahan berkualitas tinggi, nyaman untuk aktivitas sehari-hari.'
    },
    {
        id: 'boxy-miamore-1',
        brand: 'GLOOMIE SUNDAY',
        name: 'HOODIE BOXY PRINTING - MI AMORE BLACK',
        price: 199000,
        image: 'img/produk/MI AMORE B.jpg',
        thumbnails: [
            'img/produk/MI AMORE B.jpg',
            'img/produk/MI AMORE.jpg',
            'img/size chart/size chart crewneck breaker.jpg'
        ],
        description: 'Gloomie Sunday Original Indonesian Apparel. Fitting: Boxy Fit (Unisex). Material: Cotton Fleece (80% Cotton, 20% Polyester).'
    },
    {
        id: 'boxy-music',
        brand: 'GLOOMIE SUNDAY',
        name: 'TSHIRT BOXY PRINTING - MUSIC MY THERAPHY',
        price: 79000,
        image: 'img/produk/MUSIC.jpg',
        thumbnails: [
            'img/produk/MUSIC.jpg',
            'img/produk/MUSIC B.jpg'
        ],
        description: 'Sweatpants nyaman dengan desain modern untuk tampilan kasual.'
    },
    {
        id: 'sp-misty',
        brand: 'GLOOMIE SUNDAY',
        name: 'SWEATPANTS - SWEATPANTS MISTY',
        price: 179000,
        image: 'img/produk/SP MISTY.jpg',
        thumbnails: [
            'img/produk/SP MISTY.jpg',
            'img/produk/SP MISTY B.jpg'
        ],
        description: 'Jersey olahraga dengan bahan berkualitas tinggi, nyaman untuk aktivitas sehari-hari.'
    },
    {
        id: 'boxy-starlight',
        brand: 'GLOOMIE SUNDAY',
        name: 'HOODIE BOXY REFLECTIVE - STARLIGHT SHADOW',
        price: 229000,
        image: 'img/produk/STARLIGHT SHADOW.jpg',
        thumbnails: [
            'img/produk/STARLIGHT SHADOW.jpg',
            'img/produk/STARLIGHT SHADOW B.jpg',
            'img/size chart/size chart crewneck breaker.jpg'
        ],
        description: 'Gloomie Sunday Original Indonesian Apparel. Fitting: Boxy Fit (Unisex). Material: Cotton Fleece (80% Cotton, 20% Polyester).'
    },
    {
        id: 'kaos-stronger',
        brand: 'GLOOMIE SUNDAY',
        name: 'TSHIRT BOXY PRINTING - STRONGER',
        price: 79000,
        image: 'img/produk/STRONGER.jpg',
        thumbnails: [
            'img/produk/STRONGER.jpg',
            'img/produk/STRONGER B.jpg'
        ],
        description: 'Sweatpants nyaman dengan desain modern untuk tampilan kasual.'
    },
    {
        id: 'boxy-hitam-330',
        brand: 'GLOOMIE SUNDAY',
        name: 'BOXY HITAM 330',
        price: 179000,
        image: 'img/produk/BOXY HITAM 330.jpg',
        thumbnails: [
            'img/produk/BOXY HITAM 330.jpg',
            'img/produk/BOXY HITAM 330.jpg'
        ],
        description: 'Jersey olahraga dengan bahan berkualitas tinggi, nyaman untuk aktivitas sehari-hari.'
    },
    {
        id: 'boxy-zipper-1',
        brand: 'GLOOMIE SUNDAY',
        name: 'HOODIE BOXY ZIPPER - BLACK',
        price: 229000,
        image: 'img/produk/ZIPPER.jpg',
        thumbnails: [
            'img/produk/ZIPPER.jpg',
            'img/produk/ZIPPER B.jpg'
        ],
        description: 'Jersey olahraga dengan bahan berkualitas tinggi, nyaman untuk aktivitas sehari-hari.'
    }
    // ... Tambahkan puluhan produk Anda yang lain di sini dengan format yang sama
];