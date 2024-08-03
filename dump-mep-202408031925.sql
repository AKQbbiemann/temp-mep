--
-- PostgreSQL database dump
--

-- Dumped from database version 14.12 (Homebrew)
-- Dumped by pg_dump version 14.12 (Homebrew)

-- Started on 2024-08-03 19:25:33 CEST

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 3827 (class 1262 OID 18090)
-- Name: mep; Type: DATABASE; Schema: -; Owner: benbiemann
--

CREATE DATABASE mep WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE = 'C';


ALTER DATABASE mep OWNER TO benbiemann;

\connect mep

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 4 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO postgres;

--
-- TOC entry 3828 (class 0 OID 0)
-- Dependencies: 4
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA public IS 'standard public schema';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 239 (class 1259 OID 18799)
-- Name: cluster_participations; Type: TABLE; Schema: public; Owner: afs
--

CREATE TABLE public.cluster_participations (
    id bigint NOT NULL,
    cluster_id bigint NOT NULL,
    cluster_name character varying(255) NOT NULL,
    profile_id bigint NOT NULL,
    profile_name character varying(255) NOT NULL,
    competence_id integer,
    competence_name character varying(255),
    load integer NOT NULL,
    requirement_id integer NOT NULL,
    phase_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.cluster_participations OWNER TO afs;

--
-- TOC entry 238 (class 1259 OID 18798)
-- Name: cluster_participations_id_seq; Type: SEQUENCE; Schema: public; Owner: afs
--

CREATE SEQUENCE public.cluster_participations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.cluster_participations_id_seq OWNER TO afs;

--
-- TOC entry 3829 (class 0 OID 0)
-- Dependencies: 238
-- Name: cluster_participations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: afs
--

ALTER SEQUENCE public.cluster_participations_id_seq OWNED BY public.cluster_participations.id;


--
-- TOC entry 225 (class 1259 OID 18701)
-- Name: clusters; Type: TABLE; Schema: public; Owner: afs
--

CREATE TABLE public.clusters (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    description text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.clusters OWNER TO afs;

--
-- TOC entry 224 (class 1259 OID 18700)
-- Name: clusters_id_seq; Type: SEQUENCE; Schema: public; Owner: afs
--

CREATE SEQUENCE public.clusters_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.clusters_id_seq OWNER TO afs;

--
-- TOC entry 3830 (class 0 OID 0)
-- Dependencies: 224
-- Name: clusters_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: afs
--

ALTER SEQUENCE public.clusters_id_seq OWNED BY public.clusters.id;


--
-- TOC entry 229 (class 1259 OID 18726)
-- Name: competences; Type: TABLE; Schema: public; Owner: afs
--

CREATE TABLE public.competences (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.competences OWNER TO afs;

--
-- TOC entry 228 (class 1259 OID 18725)
-- Name: competences_id_seq; Type: SEQUENCE; Schema: public; Owner: afs
--

CREATE SEQUENCE public.competences_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.competences_id_seq OWNER TO afs;

--
-- TOC entry 3831 (class 0 OID 0)
-- Dependencies: 228
-- Name: competences_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: afs
--

ALTER SEQUENCE public.competences_id_seq OWNED BY public.competences.id;


--
-- TOC entry 235 (class 1259 OID 18769)
-- Name: customers; Type: TABLE; Schema: public; Owner: afs
--

CREATE TABLE public.customers (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    shortcode character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.customers OWNER TO afs;

--
-- TOC entry 234 (class 1259 OID 18768)
-- Name: customers_id_seq; Type: SEQUENCE; Schema: public; Owner: afs
--

CREATE SEQUENCE public.customers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.customers_id_seq OWNER TO afs;

--
-- TOC entry 3832 (class 0 OID 0)
-- Dependencies: 234
-- Name: customers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: afs
--

ALTER SEQUENCE public.customers_id_seq OWNED BY public.customers.id;


--
-- TOC entry 223 (class 1259 OID 18689)
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: afs
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection text NOT NULL,
    queue text NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.failed_jobs OWNER TO afs;

--
-- TOC entry 222 (class 1259 OID 18688)
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: afs
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.failed_jobs_id_seq OWNER TO afs;

--
-- TOC entry 3833 (class 0 OID 0)
-- Dependencies: 222
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: afs
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- TOC entry 227 (class 1259 OID 18712)
-- Name: load_profiles; Type: TABLE; Schema: public; Owner: afs
--

CREATE TABLE public.load_profiles (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    cluster_id bigint NOT NULL,
    description text,
    comprehensive_load integer NOT NULL,
    base_load integer NOT NULL,
    organisation_load integer NOT NULL,
    local_load integer NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    full_time_employees double precision
);


ALTER TABLE public.load_profiles OWNER TO afs;

--
-- TOC entry 237 (class 1259 OID 18782)
-- Name: load_profiles_competences; Type: TABLE; Schema: public; Owner: afs
--

CREATE TABLE public.load_profiles_competences (
    id bigint NOT NULL,
    load_profile_id bigint NOT NULL,
    competence_id bigint NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.load_profiles_competences OWNER TO afs;

--
-- TOC entry 236 (class 1259 OID 18781)
-- Name: load_profiles_competences_id_seq; Type: SEQUENCE; Schema: public; Owner: afs
--

CREATE SEQUENCE public.load_profiles_competences_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.load_profiles_competences_id_seq OWNER TO afs;

--
-- TOC entry 3834 (class 0 OID 0)
-- Dependencies: 236
-- Name: load_profiles_competences_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: afs
--

ALTER SEQUENCE public.load_profiles_competences_id_seq OWNED BY public.load_profiles_competences.id;


--
-- TOC entry 226 (class 1259 OID 18711)
-- Name: load_profiles_id_seq; Type: SEQUENCE; Schema: public; Owner: afs
--

CREATE SEQUENCE public.load_profiles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.load_profiles_id_seq OWNER TO afs;

--
-- TOC entry 3835 (class 0 OID 0)
-- Dependencies: 226
-- Name: load_profiles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: afs
--

ALTER SEQUENCE public.load_profiles_id_seq OWNED BY public.load_profiles.id;


--
-- TOC entry 218 (class 1259 OID 18667)
-- Name: migrations; Type: TABLE; Schema: public; Owner: afs
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


ALTER TABLE public.migrations OWNER TO afs;

--
-- TOC entry 217 (class 1259 OID 18666)
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: afs
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.migrations_id_seq OWNER TO afs;

--
-- TOC entry 3836 (class 0 OID 0)
-- Dependencies: 217
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: afs
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- TOC entry 221 (class 1259 OID 18682)
-- Name: password_resets; Type: TABLE; Schema: public; Owner: afs
--

CREATE TABLE public.password_resets (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


ALTER TABLE public.password_resets OWNER TO afs;

--
-- TOC entry 233 (class 1259 OID 18754)
-- Name: phases; Type: TABLE; Schema: public; Owner: afs
--

CREATE TABLE public.phases (
    id bigint NOT NULL,
    requirement_id bigint NOT NULL,
    title character varying(255) NOT NULL,
    start_date timestamp(0) without time zone NOT NULL,
    end_date timestamp(0) without time zone NOT NULL,
    dates_are_strict boolean DEFAULT false NOT NULL,
    description text NOT NULL,
    probability character varying(255) NOT NULL,
    state character varying(255) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    estimation_accuracy character varying(255),
    certainty_of_date character varying(255)
);


ALTER TABLE public.phases OWNER TO afs;

--
-- TOC entry 232 (class 1259 OID 18753)
-- Name: phases_id_seq; Type: SEQUENCE; Schema: public; Owner: afs
--

CREATE SEQUENCE public.phases_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.phases_id_seq OWNER TO afs;

--
-- TOC entry 3837 (class 0 OID 0)
-- Dependencies: 232
-- Name: phases_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: afs
--

ALTER SEQUENCE public.phases_id_seq OWNED BY public.phases.id;


--
-- TOC entry 241 (class 1259 OID 18837)
-- Name: profile_changes; Type: TABLE; Schema: public; Owner: afs
--

CREATE TABLE public.profile_changes (
    id bigint NOT NULL,
    load_profile_id bigint NOT NULL,
    user_id bigint,
    start_date date NOT NULL,
    end_date date,
    fte_change double precision,
    local_load integer,
    organisation_load integer,
    comprehensive_load integer,
    base_load integer,
    reason text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


ALTER TABLE public.profile_changes OWNER TO afs;

--
-- TOC entry 240 (class 1259 OID 18836)
-- Name: profile_changes_id_seq; Type: SEQUENCE; Schema: public; Owner: afs
--

CREATE SEQUENCE public.profile_changes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.profile_changes_id_seq OWNER TO afs;

--
-- TOC entry 3838 (class 0 OID 0)
-- Dependencies: 240
-- Name: profile_changes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: afs
--

ALTER SEQUENCE public.profile_changes_id_seq OWNED BY public.profile_changes.id;


--
-- TOC entry 231 (class 1259 OID 18733)
-- Name: requirements; Type: TABLE; Schema: public; Owner: afs
--

CREATE TABLE public.requirements (
    id bigint NOT NULL,
    title character varying(255) NOT NULL,
    type character varying(255) NOT NULL,
    owner bigint,
    creator bigint,
    customer character varying(255),
    description text NOT NULL,
    infos text,
    probability character varying(255) NOT NULL,
    start_date date NOT NULL,
    end_date date NOT NULL,
    start_date_is_strict boolean DEFAULT false NOT NULL,
    end_date_is_strict boolean DEFAULT false NOT NULL,
    time_period_description text,
    state character varying(255) NOT NULL,
    company_priority integer,
    company_priority_description text,
    requested_priority integer,
    requested_priority_description text NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    requester character varying(255)
);


ALTER TABLE public.requirements OWNER TO afs;

--
-- TOC entry 230 (class 1259 OID 18732)
-- Name: requirements_id_seq; Type: SEQUENCE; Schema: public; Owner: afs
--

CREATE SEQUENCE public.requirements_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.requirements_id_seq OWNER TO afs;

--
-- TOC entry 3839 (class 0 OID 0)
-- Dependencies: 230
-- Name: requirements_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: afs
--

ALTER SEQUENCE public.requirements_id_seq OWNED BY public.requirements.id;


--
-- TOC entry 220 (class 1259 OID 18674)
-- Name: users; Type: TABLE; Schema: public; Owner: afs
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    username character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    last_login timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    name character varying(255)
);


ALTER TABLE public.users OWNER TO afs;

--
-- TOC entry 219 (class 1259 OID 18673)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: afs
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO afs;

--
-- TOC entry 3840 (class 0 OID 0)
-- Dependencies: 219
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: afs
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- TOC entry 3607 (class 2604 OID 18802)
-- Name: cluster_participations id; Type: DEFAULT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.cluster_participations ALTER COLUMN id SET DEFAULT nextval('public.cluster_participations_id_seq'::regclass);


--
-- TOC entry 3597 (class 2604 OID 18704)
-- Name: clusters id; Type: DEFAULT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.clusters ALTER COLUMN id SET DEFAULT nextval('public.clusters_id_seq'::regclass);


--
-- TOC entry 3599 (class 2604 OID 18729)
-- Name: competences id; Type: DEFAULT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.competences ALTER COLUMN id SET DEFAULT nextval('public.competences_id_seq'::regclass);


--
-- TOC entry 3605 (class 2604 OID 18772)
-- Name: customers id; Type: DEFAULT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.customers ALTER COLUMN id SET DEFAULT nextval('public.customers_id_seq'::regclass);


--
-- TOC entry 3595 (class 2604 OID 18692)
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- TOC entry 3598 (class 2604 OID 18715)
-- Name: load_profiles id; Type: DEFAULT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.load_profiles ALTER COLUMN id SET DEFAULT nextval('public.load_profiles_id_seq'::regclass);


--
-- TOC entry 3606 (class 2604 OID 18785)
-- Name: load_profiles_competences id; Type: DEFAULT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.load_profiles_competences ALTER COLUMN id SET DEFAULT nextval('public.load_profiles_competences_id_seq'::regclass);


--
-- TOC entry 3593 (class 2604 OID 18670)
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- TOC entry 3603 (class 2604 OID 18757)
-- Name: phases id; Type: DEFAULT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.phases ALTER COLUMN id SET DEFAULT nextval('public.phases_id_seq'::regclass);


--
-- TOC entry 3608 (class 2604 OID 18840)
-- Name: profile_changes id; Type: DEFAULT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.profile_changes ALTER COLUMN id SET DEFAULT nextval('public.profile_changes_id_seq'::regclass);


--
-- TOC entry 3600 (class 2604 OID 18736)
-- Name: requirements id; Type: DEFAULT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.requirements ALTER COLUMN id SET DEFAULT nextval('public.requirements_id_seq'::regclass);


--
-- TOC entry 3594 (class 2604 OID 18677)
-- Name: users id; Type: DEFAULT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- TOC entry 3819 (class 0 OID 18799)
-- Dependencies: 239
-- Data for Name: cluster_participations; Type: TABLE DATA; Schema: public; Owner: afs
--



--
-- TOC entry 3805 (class 0 OID 18701)
-- Dependencies: 225
-- Data for Name: clusters; Type: TABLE DATA; Schema: public; Owner: afs
--

INSERT INTO public.clusters VALUES (1, 'Customer Care', NULL, '2024-08-03 16:37:27', '2024-08-03 16:37:27');
INSERT INTO public.clusters VALUES (2, 'Datacenter Security', NULL, '2024-08-03 16:37:27', '2024-08-03 16:37:27');
INSERT INTO public.clusters VALUES (3, 'Internal Services', NULL, '2024-08-03 16:37:27', '2024-08-03 16:37:27');
INSERT INTO public.clusters VALUES (4, 'Mainframe & Print Services', NULL, '2024-08-03 16:37:27', '2024-08-03 16:37:27');
INSERT INTO public.clusters VALUES (5, 'Service Delivery', NULL, '2024-08-03 16:37:27', '2024-08-03 16:37:27');
INSERT INTO public.clusters VALUES (6, 'Workplace', NULL, '2024-08-03 16:37:27', '2024-08-03 16:37:27');
INSERT INTO public.clusters VALUES (7, 'Service Design Core', NULL, '2024-08-03 16:37:27', '2024-08-03 16:37:27');
INSERT INTO public.clusters VALUES (8, 'RZ Betrieb', NULL, '2024-08-03 16:37:27', '2024-08-03 16:37:27');
INSERT INTO public.clusters VALUES (9, 'Sibling', 'Schwestergesellschaften die Anteile an der Anforderungserfüllung haben können', '2024-08-03 16:37:27', '2024-08-03 16:37:27');


--
-- TOC entry 3809 (class 0 OID 18726)
-- Dependencies: 229
-- Data for Name: competences; Type: TABLE DATA; Schema: public; Owner: afs
--

INSERT INTO public.competences VALUES (1, '2nd Level Support Infrastruktur', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (2, '2nd Level Support Netzwerk', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (3, '2nd Level Support Windows', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (4, 'Ansible', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (5, 'Automatisierung', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (6, 'Azure', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (7, 'Azure Virtual Desktop', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (8, 'Backend Entwicklung', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (9, 'Backup', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (10, 'Betankung', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (11, 'Betriebsunterstützung', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (12, 'Citrix', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (13, 'Client Design', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (14, 'Cloud PC', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (15, 'Configuration Management', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (16, 'Containerisierung', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (17, 'DAMS', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (18, 'Datenbank', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (19, 'Datenprozesse', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (20, 'DCI', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (21, 'DC-IMAC', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (22, 'DHCP', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (23, 'DNS', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (24, 'EndPoint Security', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (25, 'Entra ID', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (26, 'Exchange', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (27, 'Faktura-vorbereitung', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (28, 'Firewall', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (29, 'Frontend Entwicklung', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (30, 'Hands-On', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (31, 'IZ-Housing', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (32, 'Key Account Management', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (33, 'Kundenbetreuung', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (34, 'LAN', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (35, 'Mainframe', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (36, 'Messaging OnPrem', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (37, 'MFA', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (38, 'Monitoring', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (39, 'OnPrem Services', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (40, 'Onsite Support', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (41, 'Open Source', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (42, 'Open-Systems-OS', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (43, 'Paketierung', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (44, 'PreSales', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (45, 'Printer Management', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (46, 'Produktionsdruck', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (47, 'Produktmanagement Workplace', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (48, 'Projektleitung', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (49, 'Reporting', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (50, 'Rollout', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (51, 'Routing', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (52, 'RZ-Verkabelung', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (53, 'RZ-Begleitung', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (54, 'Satellite', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (55, 'Scripting', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (56, 'Server-Virtualisierung', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (57, 'Service Design', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (58, 'Service Desk', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (59, 'Service Lifecycle', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (60, 'Software Design', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (61, 'Storage', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (62, 'SW Verteilung', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (63, 'Switching', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (64, 'Vertragsmanagement Kunde', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (65, 'Vertrieb', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (66, 'Visualisierung', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (67, 'WAN', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (68, 'Werkschutz / DCS', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (69, 'Windows Server', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (70, 'WLAN', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (71, 'Datenschutz', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (72, 'Informationssicherheit', '2024-08-03 16:37:22', '2024-08-03 16:37:22');
INSERT INTO public.competences VALUES (73, 'Management', '2024-08-03 16:37:22', '2024-08-03 16:37:22');


--
-- TOC entry 3815 (class 0 OID 18769)
-- Dependencies: 235
-- Data for Name: customers; Type: TABLE DATA; Schema: public; Owner: afs
--



--
-- TOC entry 3803 (class 0 OID 18689)
-- Dependencies: 223
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: afs
--



--
-- TOC entry 3807 (class 0 OID 18712)
-- Dependencies: 227
-- Data for Name: load_profiles; Type: TABLE DATA; Schema: public; Owner: afs
--

INSERT INTO public.load_profiles VALUES (1, 'Service Desk', 1, '', 20, 25, 30, 25, '2024-08-03 16:37:56', '2024-08-03 17:23:20', 6);


--
-- TOC entry 3817 (class 0 OID 18782)
-- Dependencies: 237
-- Data for Name: load_profiles_competences; Type: TABLE DATA; Schema: public; Owner: afs
--



--
-- TOC entry 3798 (class 0 OID 18667)
-- Dependencies: 218
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: afs
--

INSERT INTO public.migrations VALUES (1, '2024_06_07_000000_create_users_table', 1);
INSERT INTO public.migrations VALUES (2, '2024_06_07_000001_create_password_resets_table', 1);
INSERT INTO public.migrations VALUES (3, '2024_06_07_000002_create_failed_jobs_table', 1);
INSERT INTO public.migrations VALUES (4, '2024_06_07_000003_create_clusters_table', 1);
INSERT INTO public.migrations VALUES (5, '2024_06_07_000004_create_load_profiles_table', 1);
INSERT INTO public.migrations VALUES (6, '2024_06_07_000005_create_competences_table', 1);
INSERT INTO public.migrations VALUES (7, '2024_06_07_000006_create_requirements_table', 1);
INSERT INTO public.migrations VALUES (8, '2024_06_07_000007_create_phases_table', 1);
INSERT INTO public.migrations VALUES (9, '2024_06_07_000008_create_customers_table', 1);
INSERT INTO public.migrations VALUES (10, '2024_06_07_000010_create_load_profiles_competences_table', 1);
INSERT INTO public.migrations VALUES (11, '2024_06_07_000011_create_cluster_participations_table', 1);
INSERT INTO public.migrations VALUES (12, '2024_07_03_171513_add_estimation_and_certainty_to_phases', 1);
INSERT INTO public.migrations VALUES (13, '2024_07_03_172113_add_requester_to_requirements', 1);
INSERT INTO public.migrations VALUES (14, '2024_07_05_000000_add_full_time_employees_to_load_profiles', 1);
INSERT INTO public.migrations VALUES (15, '2024_07_05_000001_create_employee_changes_table', 1);
INSERT INTO public.migrations VALUES (16, '2024_07_18_131132_add_name_to_users', 1);
INSERT INTO public.migrations VALUES (17, '2024_08_01_114232_drop_employee_changes_table', 1);
INSERT INTO public.migrations VALUES (18, '2024_08_01_124332_create_profile_changes_table', 1);


--
-- TOC entry 3801 (class 0 OID 18682)
-- Dependencies: 221
-- Data for Name: password_resets; Type: TABLE DATA; Schema: public; Owner: afs
--



--
-- TOC entry 3813 (class 0 OID 18754)
-- Dependencies: 233
-- Data for Name: phases; Type: TABLE DATA; Schema: public; Owner: afs
--



--
-- TOC entry 3821 (class 0 OID 18837)
-- Dependencies: 241
-- Data for Name: profile_changes; Type: TABLE DATA; Schema: public; Owner: afs
--

INSERT INTO public.profile_changes VALUES (1, 1, NULL, '2024-08-03', '2024-08-03', 6, 25, 30, 20, 25, 'because i can', '2024-08-03 17:23:20', '2024-08-03 17:23:20');


--
-- TOC entry 3811 (class 0 OID 18733)
-- Dependencies: 231
-- Data for Name: requirements; Type: TABLE DATA; Schema: public; Owner: afs
--



--
-- TOC entry 3800 (class 0 OID 18674)
-- Dependencies: 220
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: afs
--



--
-- TOC entry 3841 (class 0 OID 0)
-- Dependencies: 238
-- Name: cluster_participations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: afs
--

SELECT pg_catalog.setval('public.cluster_participations_id_seq', 1, false);


--
-- TOC entry 3842 (class 0 OID 0)
-- Dependencies: 224
-- Name: clusters_id_seq; Type: SEQUENCE SET; Schema: public; Owner: afs
--

SELECT pg_catalog.setval('public.clusters_id_seq', 9, true);


--
-- TOC entry 3843 (class 0 OID 0)
-- Dependencies: 228
-- Name: competences_id_seq; Type: SEQUENCE SET; Schema: public; Owner: afs
--

SELECT pg_catalog.setval('public.competences_id_seq', 73, true);


--
-- TOC entry 3844 (class 0 OID 0)
-- Dependencies: 234
-- Name: customers_id_seq; Type: SEQUENCE SET; Schema: public; Owner: afs
--

SELECT pg_catalog.setval('public.customers_id_seq', 1, false);


--
-- TOC entry 3845 (class 0 OID 0)
-- Dependencies: 222
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: afs
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- TOC entry 3846 (class 0 OID 0)
-- Dependencies: 236
-- Name: load_profiles_competences_id_seq; Type: SEQUENCE SET; Schema: public; Owner: afs
--

SELECT pg_catalog.setval('public.load_profiles_competences_id_seq', 1, false);


--
-- TOC entry 3847 (class 0 OID 0)
-- Dependencies: 226
-- Name: load_profiles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: afs
--

SELECT pg_catalog.setval('public.load_profiles_id_seq', 1, true);


--
-- TOC entry 3848 (class 0 OID 0)
-- Dependencies: 217
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: afs
--

SELECT pg_catalog.setval('public.migrations_id_seq', 18, true);


--
-- TOC entry 3849 (class 0 OID 0)
-- Dependencies: 232
-- Name: phases_id_seq; Type: SEQUENCE SET; Schema: public; Owner: afs
--

SELECT pg_catalog.setval('public.phases_id_seq', 1, false);


--
-- TOC entry 3850 (class 0 OID 0)
-- Dependencies: 240
-- Name: profile_changes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: afs
--

SELECT pg_catalog.setval('public.profile_changes_id_seq', 1, true);


--
-- TOC entry 3851 (class 0 OID 0)
-- Dependencies: 230
-- Name: requirements_id_seq; Type: SEQUENCE SET; Schema: public; Owner: afs
--

SELECT pg_catalog.setval('public.requirements_id_seq', 1, false);


--
-- TOC entry 3852 (class 0 OID 0)
-- Dependencies: 219
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: afs
--

SELECT pg_catalog.setval('public.users_id_seq', 1, false);


--
-- TOC entry 3639 (class 2606 OID 18806)
-- Name: cluster_participations cluster_participations_pkey; Type: CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.cluster_participations
    ADD CONSTRAINT cluster_participations_pkey PRIMARY KEY (id);


--
-- TOC entry 3619 (class 2606 OID 18710)
-- Name: clusters clusters_name_unique; Type: CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.clusters
    ADD CONSTRAINT clusters_name_unique UNIQUE (name);


--
-- TOC entry 3621 (class 2606 OID 18708)
-- Name: clusters clusters_pkey; Type: CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.clusters
    ADD CONSTRAINT clusters_pkey PRIMARY KEY (id);


--
-- TOC entry 3625 (class 2606 OID 18731)
-- Name: competences competences_pkey; Type: CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.competences
    ADD CONSTRAINT competences_pkey PRIMARY KEY (id);


--
-- TOC entry 3631 (class 2606 OID 18778)
-- Name: customers customers_name_unique; Type: CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.customers
    ADD CONSTRAINT customers_name_unique UNIQUE (name);


--
-- TOC entry 3633 (class 2606 OID 18776)
-- Name: customers customers_pkey; Type: CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.customers
    ADD CONSTRAINT customers_pkey PRIMARY KEY (id);


--
-- TOC entry 3635 (class 2606 OID 18780)
-- Name: customers customers_shortcode_unique; Type: CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.customers
    ADD CONSTRAINT customers_shortcode_unique UNIQUE (shortcode);


--
-- TOC entry 3615 (class 2606 OID 18697)
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- TOC entry 3617 (class 2606 OID 18699)
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- TOC entry 3637 (class 2606 OID 18787)
-- Name: load_profiles_competences load_profiles_competences_pkey; Type: CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.load_profiles_competences
    ADD CONSTRAINT load_profiles_competences_pkey PRIMARY KEY (id);


--
-- TOC entry 3623 (class 2606 OID 18719)
-- Name: load_profiles load_profiles_pkey; Type: CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.load_profiles
    ADD CONSTRAINT load_profiles_pkey PRIMARY KEY (id);


--
-- TOC entry 3610 (class 2606 OID 18672)
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- TOC entry 3629 (class 2606 OID 18762)
-- Name: phases phases_pkey; Type: CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.phases
    ADD CONSTRAINT phases_pkey PRIMARY KEY (id);


--
-- TOC entry 3641 (class 2606 OID 18844)
-- Name: profile_changes profile_changes_pkey; Type: CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.profile_changes
    ADD CONSTRAINT profile_changes_pkey PRIMARY KEY (id);


--
-- TOC entry 3627 (class 2606 OID 18742)
-- Name: requirements requirements_pkey; Type: CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.requirements
    ADD CONSTRAINT requirements_pkey PRIMARY KEY (id);


--
-- TOC entry 3612 (class 2606 OID 18681)
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- TOC entry 3613 (class 1259 OID 18687)
-- Name: password_resets_email_index; Type: INDEX; Schema: public; Owner: afs
--

CREATE INDEX password_resets_email_index ON public.password_resets USING btree (email);


--
-- TOC entry 3648 (class 2606 OID 18807)
-- Name: cluster_participations cluster_participations_cluster_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.cluster_participations
    ADD CONSTRAINT cluster_participations_cluster_id_foreign FOREIGN KEY (cluster_id) REFERENCES public.clusters(id) ON DELETE CASCADE;


--
-- TOC entry 3650 (class 2606 OID 18817)
-- Name: cluster_participations cluster_participations_phase_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.cluster_participations
    ADD CONSTRAINT cluster_participations_phase_id_foreign FOREIGN KEY (phase_id) REFERENCES public.phases(id) ON DELETE CASCADE;


--
-- TOC entry 3649 (class 2606 OID 18812)
-- Name: cluster_participations cluster_participations_profile_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.cluster_participations
    ADD CONSTRAINT cluster_participations_profile_id_foreign FOREIGN KEY (profile_id) REFERENCES public.load_profiles(id) ON DELETE CASCADE;


--
-- TOC entry 3642 (class 2606 OID 18720)
-- Name: load_profiles load_profiles_cluster_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.load_profiles
    ADD CONSTRAINT load_profiles_cluster_id_foreign FOREIGN KEY (cluster_id) REFERENCES public.clusters(id) ON DELETE CASCADE;


--
-- TOC entry 3647 (class 2606 OID 18793)
-- Name: load_profiles_competences load_profiles_competences_competence_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.load_profiles_competences
    ADD CONSTRAINT load_profiles_competences_competence_id_foreign FOREIGN KEY (competence_id) REFERENCES public.competences(id) ON DELETE CASCADE;


--
-- TOC entry 3646 (class 2606 OID 18788)
-- Name: load_profiles_competences load_profiles_competences_load_profile_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.load_profiles_competences
    ADD CONSTRAINT load_profiles_competences_load_profile_id_foreign FOREIGN KEY (load_profile_id) REFERENCES public.load_profiles(id) ON DELETE CASCADE;


--
-- TOC entry 3645 (class 2606 OID 18763)
-- Name: phases phases_requirement_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.phases
    ADD CONSTRAINT phases_requirement_id_foreign FOREIGN KEY (requirement_id) REFERENCES public.requirements(id) ON DELETE CASCADE;


--
-- TOC entry 3651 (class 2606 OID 18845)
-- Name: profile_changes profile_changes_load_profile_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.profile_changes
    ADD CONSTRAINT profile_changes_load_profile_id_foreign FOREIGN KEY (load_profile_id) REFERENCES public.load_profiles(id);


--
-- TOC entry 3652 (class 2606 OID 18850)
-- Name: profile_changes profile_changes_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.profile_changes
    ADD CONSTRAINT profile_changes_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id);


--
-- TOC entry 3644 (class 2606 OID 18748)
-- Name: requirements requirements_creator_foreign; Type: FK CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.requirements
    ADD CONSTRAINT requirements_creator_foreign FOREIGN KEY (creator) REFERENCES public.users(id);


--
-- TOC entry 3643 (class 2606 OID 18743)
-- Name: requirements requirements_owner_foreign; Type: FK CONSTRAINT; Schema: public; Owner: afs
--

ALTER TABLE ONLY public.requirements
    ADD CONSTRAINT requirements_owner_foreign FOREIGN KEY (owner) REFERENCES public.users(id);


-- Completed on 2024-08-03 19:25:34 CEST

--
-- PostgreSQL database dump complete
--

