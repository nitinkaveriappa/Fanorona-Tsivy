CREATE SCHEMA fanodb;
USE fanodb;
-- Creating the Player Master table -- 
CREATE TABLE PL_MST(player_id INT NOT NULL,
					player_name VARCHAR(30) NOT NULL,
                    player_email VARCHAR(25) NOT NULL,
                    player_password VARCHAR(100) NOT NULL,
                    player_verified INT NOT NULL,
                    PRIMARY KEY (player_id));

-- Creating the Player Stats table --                     
CREATE TABLE PL_STS(player_id INT NOT NULL,
					win_count INT NULL,
                    draw_count INT NULL,
                    loss_count INT NULL,
                    PRIMARY KEY (player_id),
                    FOREIGN KEY (player_id) REFERENCES PL_MST(player_id) ON DELETE CASCADE);
                    
-- Creating the Flag List table -- 
CREATE TABLE FLG_LS(player_id INT NOT NULL,
					login_flag INT NOT NULL,
                    lobby_flag INT NOT NULL,
                    ingame_flag INT NOT NULL,
                    PRIMARY KEY (player_id),
                    FOREIGN KEY (player_id) REFERENCES PL_MST(player_id) ON DELETE CASCADE);
                    
-- Creating the Game Master table --
CREATE TABLE GM_MST(game_id INT NOT NULL,
					player_id_1 INT NOT NULL,
                    player_id_2 INT NOT NULL,
                    game_start DATETIME NOT NULL,
                    game_end DATETIME NULL,
                    PRIMARY KEY (game_id, player_id_1, player_id_2),
                    FOREIGN KEY (player_id_1) REFERENCES PL_MST(player_id) ON DELETE CASCADE,
					FOREIGN KEY (player_id_2) REFERENCES PL_MST(player_id) ON DELETE CASCADE);
                    
-- Creating the Game State table --
CREATE TABLE GM_ST(game_id INT NOT NULL,
					node_list VARCHAR(50) NOT NULL,
                    move_count INT NOT NULL,
                    restricted_nodes VARCHAR(40) NULL,
                    FOREIGN KEY (game_id) REFERENCES GM_MST(game_id) ON DELETE CASCADE);
                    
 -- Creating the Moves List table --                   
 CREATE TABLE MV_LS(game_id INT NOT NULL,
					player_id INT NOT NULL,
                    move INT NOT NULL,
                    FOREIGN KEY (game_id) REFERENCES GM_MST(game_id) ON DELETE CASCADE,
                    FOREIGN KEY (player_id) REFERENCES PL_MST(player_id) ON DELETE CASCADE);
                    
-- Creating the verify list table --
CREATE TABLE VR_LS(player_id INT NOT NULL,
				   verify_code VARCHAR(40) NOT NULL,
                   PRIMARY KEY (player_id),
                   FOREIGN KEY (player_id) REFERENCES PL_MST(player_id) ON DELETE CASCADE);