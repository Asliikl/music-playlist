let playBtn = document.querySelectorAll('.playlist .box-container .box .play');
let musicPlayer = document.querySelector('.music-player');
let musicAlbum = musicPlayer.querySelector('.album');
let musicName = musicPlayer.querySelector('.name');
let musicArtist = musicPlayer.querySelector('.artist');
let music = musicPlayer.querySelector('.music');
let shuffleBtn = document.querySelector('#shuffleBtn');
let currentIndex = 0; 
let songs = [];
let isShuffle = true;  

document.querySelectorAll('.playlist .box-container .box').forEach((box, index) => {
    let name = box.querySelector('.name').innerText;
    let album = box.querySelector('.album').src;
    let artist = box.querySelector('.artist').innerText;
    let musicSrc = box.querySelector('.play').getAttribute('data-src');
    
    songs.push({
        name: name,
        album: album,
        artist: artist,
        musicSrc: musicSrc
    });
});

function playMusic(index) {
    let song = songs[index];
    musicAlbum.src = song.album;
    musicName.innerHTML = song.name;
    musicArtist.innerHTML = song.artist;
    music.src = song.musicSrc;

    musicPlayer.classList.add('active');
    music.play();
}

playBtn.forEach((play, index) => {
    play.onclick = () => {
        currentIndex = index; 
        playMusic(currentIndex);
    };
});

music.addEventListener('ended', () => {
    if (isShuffle) {
        let newIndex;
        do {
            newIndex = Math.floor(Math.random() * songs.length);
        } while (newIndex === currentIndex);
        currentIndex = newIndex;
    }
    playMusic(currentIndex); 
});

// "Karışık Çal" 
shuffleBtn.onclick = () => {
    currentIndex = Math.floor(Math.random() * songs.length);
    playMusic(currentIndex);
};

document.querySelector('#close').onclick = () => {
    musicPlayer.classList.remove('active');
    music.pause();
};
