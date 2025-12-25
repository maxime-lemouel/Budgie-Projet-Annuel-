-- Table: public.roles

-- DROP TABLE IF EXISTS public."roles";

CREATE TABLE IF NOT EXISTS public."roles"
(
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    name character varying(10) UNIQUE NOT NULL -- 'admin' / 'user'
    )

    TABLESPACE pg_default;

INSERT INTO public.roles (name) VALUES ('admin'), ('user');

ALTER TABLE IF EXISTS public."roles"
    OWNER to devuser;

-- Table: public.user

-- DROP TABLE IF EXISTS public."user";

CREATE TABLE IF NOT EXISTS public."user"
(
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    firstname character varying(50) COLLATE pg_catalog."default",
    lastname character varying(100) COLLATE pg_catalog."default",
    email character varying(320) COLLATE pg_catalog."default" NOT NULL,
    pwd character varying(255) COLLATE pg_catalog."default" NOT NULL,
    is_active boolean DEFAULT false,
    date_created date NOT NULL,
    date_updated date,
    role_id integer,
    CONSTRAINT fk_role FOREIGN KEY (role_id) REFERENCES public."roles"(id)
    )

    TABLESPACE pg_default;

ALTER TABLE IF EXISTS public."user"
    OWNER to devuser;

-- Table: public.user_tokens

-- DROP TABLE IF EXISTS public."user_tokens";

CREATE TABLE IF NOT EXISTS public."user_tokens"
(
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    user_id integer NOT NULL,
    token character varying(255) NOT NULL,
    type character varying(10) NOT NULL, -- 'validation' / 'reset'
    expiry timestamp,
    created_at date DEFAULT CURRENT_DATE,
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES public."user"(id) ON DELETE CASCADE

    )

    TABLESPACE pg_default;

ALTER TABLE IF EXISTS public."user_tokens"
    OWNER to devuser;

-- Table: public.pages

-- DROP TABLE IF EXISTS public."pages";

CREATE TABLE IF NOT EXISTS public."pages"
(
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    title character varying(60) NOT NULL,
    slug character varying(200) UNIQUE NOT NULL,
    content text,
    meta_description character varying(158),
    is_published boolean DEFAULT FALSE,
    created_at date DEFAULT CURRENT_DATE,
    updated_at date,
    author_id integer,
    CONSTRAINT fk_author FOREIGN KEY (author_id) REFERENCES public."user"(id)
    )

    TABLESPACE pg_default;

ALTER TABLE IF EXISTS public."pages"
    OWNER to devuser;


-- Table: public.compte 
CREATE TABLE IF NOT EXISTS public.compte
(
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    user_id integer NOT NULL,
    nom character varying(100) NOT NULL,
    description text,
    date_creation date NOT NULL DEFAULT CURRENT_DATE,
    taux_remuneration numeric(5, 2),
    taux_imposition numeric(5, 2),
    date_updated date,
    CONSTRAINT fk_user_compte FOREIGN KEY (user_id)
        REFERENCES public."user" (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE CASCADE
);

-- Table: public.depense 
CREATE TABLE IF NOT EXISTS public.depense
(
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    compte_id integer NOT NULL,
    nom character varying(100) NOT NULL,
    description text,
    date_debut timestamp without time zone NOT NULL,
    date_fin timestamp without time zone,
    montant numeric(10, 2) NOT NULL,
    date_updated date,
    CONSTRAINT fk_compte_depense FOREIGN KEY (compte_id)
        REFERENCES public.compte (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE CASCADE
);

-- Table: public.duree_depense 
CREATE TABLE IF NOT EXISTS public.duree_depense
(
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    depense_id integer NOT NULL,
    ponctuelle boolean NOT NULL DEFAULT TRUE,
    iteration integer,
    CONSTRAINT fk_depense_duree FOREIGN KEY (depense_id)
        REFERENCES public.depense (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE CASCADE
);

-- Table: public.revenus 
CREATE TABLE IF NOT EXISTS public.revenus
(
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    compte_id integer NOT NULL,
    nom character varying(100) NOT NULL,
    description text,
    date_debut timestamp without time zone NOT NULL,
    date_fin timestamp without time zone,
    montant numeric(10, 2) NOT NULL,
    date_updated date,
    CONSTRAINT fk_compte_revenus FOREIGN KEY (compte_id)
        REFERENCES public.compte (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE CASCADE
);

-- Table: public.duree_revenus
CREATE TABLE IF NOT EXISTS public.duree_revenus
(
    id integer GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    revenus_id integer NOT NULL,
    ponctuelle boolean NOT NULL DEFAULT TRUE,
    iteration integer,
    CONSTRAINT fk_revenus_duree FOREIGN KEY (revenus_id)
        REFERENCES public.revenus (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE CASCADE
);
