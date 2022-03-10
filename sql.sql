	CREATE TABLE public."user"
	(
	    id serial NOT NULL,
	    name varchar,
	    password varchar,
	    age int,
	    PRIMARY KEY (id)
	)
	INSERT INTO public.user (name,age,password) VALUES ('admin',34, 'admin');