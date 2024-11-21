-- Database
CREATE DATABASE elgato;

-- Tabel user
CREATE TABLE user (
    UserID INT(11) PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(255) NOT NULL,
    Password VARCHAR(255) NOT NULL,
    Email VARCHAR(255),
    NamaLengkap VARCHAR(255),
    Alamat TEXT,
    ProfilePicture VARCHAR(255)
);

-- Tabel album
CREATE TABLE album (
    AlbumID INT(11) PRIMARY KEY AUTO_INCREMENT,
    NamaAlbum VARCHAR(255),
    Deskripsi TEXT,
    TanggalDibuat DATE,
    UserID INT(11),
    FOREIGN KEY (UserID) REFERENCES user(UserID) ON DELETE CASCADE
);

-- Tabel foto
CREATE TABLE foto (
    FotoID INT(11) PRIMARY KEY AUTO_INCREMENT,
    JudulFoto VARCHAR(255),
    DeskripsiFoto TEXT,
    TanggalUnggah DATE,
    LokasiFile VARCHAR(255),
    AlbumID INT(11),
    UserID INT(11),
    FOREIGN KEY (AlbumID) REFERENCES album(AlbumID) ON DELETE SET NULL,
    FOREIGN KEY (UserID) REFERENCES user(UserID) ON DELETE CASCADE
);

-- Tabel komentarfoto
CREATE TABLE komentarfoto (
    KomentarID INT(11) PRIMARY KEY AUTO_INCREMENT,
    FotoID INT(11),
    UserID INT(11),
    IsiKomentar TEXT,
    TanggalKomentar DATE,
    FOREIGN KEY (FotoID) REFERENCES foto(FotoID) ON DELETE CASCADE,
    FOREIGN KEY (UserID) REFERENCES user(UserID) ON DELETE CASCADE
);

-- Tabel likefoto
CREATE TABLE likefoto (
    LikeID INT(11) PRIMARY KEY AUTO_INCREMENT,
    FotoID INT(11),
    UserID INT(11),
    TanggalLike DATE,
    FOREIGN KEY (FotoID) REFERENCES foto(FotoID) ON DELETE CASCADE,
    FOREIGN KEY (UserID) REFERENCES user(UserID) ON DELETE CASCADE
);
