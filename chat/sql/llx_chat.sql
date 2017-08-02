-- <one line to give the program's name and a brief idea of what it does.>
-- Copyright (C) <year>  <name of author>
--
-- This program is free software: you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation, either version 3 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program.  If not, see <http://www.gnu.org/licenses/>.

CREATE TABLE llx_chat_msg(
	rowid INTEGER AUTO_INCREMENT PRIMARY KEY,
	entity INTEGER DEFAULT 1 NOT NULL,
	fk_user INTEGER NOT NULL,
        post_time DATETIME NOT NULL,
        text TEXT,
        fk_user_to INTEGER NULL,
        fk_attachment INTEGER NULL,
        status INTEGER DEFAULT 0 NOT NULL
);

CREATE TABLE llx_chat_online(
	online_id INTEGER AUTO_INCREMENT PRIMARY KEY,
	online_ip VARCHAR(100) NOT NULL,
	online_user INTEGER NOT NULL,
        online_time DATETIME NOT NULL,
        online_status INTEGER DEFAULT 0 NOT NULL
);

CREATE TABLE llx_chat_msg_attachment(
	rowid INTEGER AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(250) NOT NULL,
        type VARCHAR(100) NULL,
        size INTEGER NULL
);
