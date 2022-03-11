	CREATE TABLE public."user"
	(
	    id serial NOT NULL,
	    name varchar,
	    password varchar,
	    age int,
	    PRIMARY KEY (id)
	)

    CREATE TABLE public."token"
	(
	    id serial NOT NULL,
	    token text,
	    valid int
	)
	INSERT INTO public.user (name,age,password) VALUES ('admin',34, 'admin');