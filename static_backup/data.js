// Chap Connect - Talents Database
const talentsData = [
  {
    id: "zuuhtwalib",
    name: "Zuuh Twalib - PLANET FM",
    category: "journalist",
    categoryLabel: "Journalist/Media",
    description: "Experienced radio journalist and media presenter at Planet FM, dedicated to bringing the latest updates, culture news, and high-energy broadcasts to the community.",
    image: "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1580489944761-15a19d654956?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4"
    ]
  },
  {
    id: "chapconnect",
    name: "Chap Connect",
    category: "management",
    categoryLabel: "Management",
    description: "Premium entertainment talent management agency representing elite artists, actors, directors, and creators across East Africa.",
    image: "https://images.unsplash.com/photo-1557804506-669a67965ba0?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1557804506-669a67965ba0?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4"
    ]
  },
  {
    id: "jaycombat",
    name: "Jay Combat",
    category: "musicartist",
    categoryLabel: "Singeli Music Artist",
    description: "High-speed Singeli artist pushing the boundaries of traditional Tanzanian street music with rapid lyrics and infectious high-tempo beats.",
    image: "https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4"
    ]
  },
  {
    id: "bambocomedian",
    name: "Bambo Comedian",
    category: "comedian",
    categoryLabel: "Comedian",
    description: "Versatile stand-up comedian and comic creator known for hilarious sketches, observational humor, and captivating live audiences.",
    image: "https://images.unsplash.com/photo-1517841905240-472988babdf9?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-girl-in-neon-sign-backlight-41838-large.mp4"
    ]
  },
  {
    id: "mafuru",
    name: "Mafuru Comedian",
    category: "comedian",
    categoryLabel: "Comedian",
    description: "Energetic comedy artist focusing on physical comedy, street interviews, and engaging social media content that keeps thousands laughing daily.",
    image: "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-girl-in-neon-sign-backlight-41838-large.mp4"
    ]
  },
  {
    id: "melodiesclassicband",
    name: "The Melodies Classic Band",
    category: "musicband",
    categoryLabel: "Live Music Band",
    description: "Premium live performance band playing classic fusion, Afro-jazz, and contemporary pop covers. Available for weddings, corporate galas, and VIP shows.",
    image: "https://images.unsplash.com/photo-1501386761578-eac5c94b800a?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1501386761578-eac5c94b800a?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4"
    ]
  },
  {
    id: "directorclevorx",
    name: "Director Clevor X",
    category: "videodirector",
    categoryLabel: "Director, Producer",
    description: "Award-winning cinematographer and director specializing in music videos, commercials, and visual storytelling that transforms brands.",
    image: "https://images.unsplash.com/photo-1485846234645-a62644f84728?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1485846234645-a62644f84728?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4"
    ]
  },
  {
    id: "minobongotv",
    name: "Mino Bongo TV",
    category: "onlinetv",
    categoryLabel: "Online TV",
    description: "Leading online television channel covering music releases, artist gossip, cultural news, and trending social debates across Tanzania.",
    image: "https://images.unsplash.com/photo-1626379616459-b2ce1d9decbc?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1626379616459-b2ce1d9decbc?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1598257006458-087169a1f08d?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4"
    ]
  },
  {
    id: "rude",
    name: "Rude",
    category: "videodirector",
    categoryLabel: "Director/Artist/Story Writer",
    description: "Multi-talented video director and screenplay writer known for deep visual narratives, musicality, and cinematic lighting setups.",
    image: "https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-holding-a-video-camera-recording-footage-41589-large.mp4"
    ]
  },
  {
    id: "lwitikomatumba",
    name: "Lwitiko Matumba",
    category: "musicartist",
    categoryLabel: "Artist/Kutongoa",
    description: "Creative fusion singer and vocal artist exploring traditional styles combined with modern afro-beats rhythms.",
    image: "https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4"
    ]
  },
  {
    id: "aryana",
    name: "Aryana",
    category: "musicartist",
    categoryLabel: "Actress, Gospel Musician",
    description: "Gospel musician and talented film actress blending soulful melodies with inspirational and powerful message-driven lyrics.",
    image: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4"
    ]
  },
  {
    id: "tyserxl",
    name: "Tyser XL",
    category: "musicartist",
    categoryLabel: "Artist/HipHOP",
    description: "Fierce HipHop lyricist known for hard-hitting delivery, socially conscious bars, and a commanding mic presence.",
    image: "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4"
    ]
  },
  {
    id: "popweezy",
    name: "Pop Weezy",
    category: "musicartist",
    categoryLabel: "Artist/HIPHOP & Drill",
    description: "Rising star of the East African drill scene, delivering heavy 808s and fast-paced rhymes about modern urban life.",
    image: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4"
    ]
  },
  {
    id: "zellahmsekeni",
    name: "Zellah Msekeni",
    category: "musicartist",
    categoryLabel: "Artist/Gospel/Dancer/Actor",
    description: "Expressive multi-talent bridging high-energy spiritual dancing, acting, and gospel recording to inspire youth across regions.",
    image: "https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4"
    ]
  },
  {
    id: "ukhtymayrah",
    name: "Ukhty Mayrah",
    category: "contentcreator",
    categoryLabel: "Content Creator",
    description: "Creative digital designer and social media influencer making engaging lifestyle videos, reviews, and community stories.",
    image: "https://images.unsplash.com/photo-1580489944761-15a19d654956?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1580489944761-15a19d654956?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-girl-in-neon-sign-backlight-41838-large.mp4"
    ]
  },
  {
    id: "directorngalawa",
    name: "Director Ngalawa",
    category: "videodirector",
    categoryLabel: "Video Director",
    description: "Prominent visual creator specializing in high-definition video production, narrative-driven cinematography, and top-tier musical edits.",
    image: "https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1485846234645-a62644f84728?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1542204172-e7052809f85e?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1598899134739-24c46f58b8c0?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1518609878373-06d740f60d8b?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-holding-a-video-camera-recording-footage-41589-large.mp4"
    ]
  },
  {
    id: "ghosthandbeatz",
    name: "Ghosthand Tz",
    category: "musicproducer",
    categoryLabel: "Music Producer",
    description: "Elite beatmaker and audio engineer forging iconic Afro-pop, Bongo Flava, and Trap backdrops for superstars.",
    image: "https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1598653222000-6b7b7a552625?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4"
    ]
  },
  {
    id: "nazzerplanetfm",
    name: "Nazzer Controller Planet FM",
    category: "journalist",
    categoryLabel: "Journalist, Entertainment",
    description: "Highly energetic entertainment pundit and dynamic host bringing celebrity exclusives and fresh music news to the radio airwaves.",
    image: "https://images.unsplash.com/photo-1517841905240-472988babdf9?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4"
    ]
  },
  {
    id: "producerelly",
    name: "Producer Elly",
    category: "musicproducer",
    categoryLabel: "Music Producer",
    description: "Renowned record producer and master mixing expert who has sculpted numerous top-charting tracks and emotional ballads.",
    image: "https://images.unsplash.com/photo-1598653222000-6b7b7a552625?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1598653222000-6b7b7a552625?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4"
    ]
  },
  {
    id: "cinehood",
    name: "Cinehood",
    category: "videodirector",
    categoryLabel: "Video Director",
    description: "Creative film studio and video director group creating high-end television advertisements and cinematic musical blockbusters.",
    image: "https://images.unsplash.com/photo-1518609878373-06d740f60d8b?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1518609878373-06d740f60d8b?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1485846234645-a62644f84728?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4"
    ]
  },
  {
    id: "directornasonga",
    name: "Director Nasonga",
    category: "videodirector",
    categoryLabel: "Movie Video Director",
    description: "Cinematic director known for dramatic shorts, full-length storytelling movies, and visually stunning musical releases.",
    image: "https://images.unsplash.com/photo-1598899134739-24c46f58b8c0?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1598899134739-24c46f58b8c0?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-holding-a-video-camera-recording-footage-41589-large.mp4"
    ]
  },
  {
    id: "winnerman",
    name: "Winner Man",
    category: "contentcreator",
    categoryLabel: "Content Creator",
    description: "Inspiring digital video blogger publishing creative challenges, social experiments, and comedy clips across multiple online platforms.",
    image: "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=600&auto=format&fit=crop&q=80",
    country: "East Africa Tanzania",
    phone: "+255 710 383 352",
    links: {
      instagram: "https://instagram.com",
      facebook: "https://facebook.com",
      tiktok: "https://tiktok.com",
      youtube: "https://youtube.com"
    },
    photos: [
      "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&auto=format&fit=crop&q=80",
      "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800&auto=format&fit=crop&q=80"
    ],
    videos: [
      "https://assets.mixkit.co/videos/preview/mixkit-holding-a-video-camera-recording-footage-41589-large.mp4"
    ]
  }
];
