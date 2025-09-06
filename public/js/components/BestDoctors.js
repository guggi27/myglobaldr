function BestDoctors() {

    // const [data, setData] = React.useState([
    //     { name: "Dr. Sadia", img: (baseUrl + "/img/doctors/1.png"), ratings: 4.5, status: "Available", speciality: "Orthopedic", location: "Lahore, Pakistan" },
    //     { name: "Dr. Kamran", img: (baseUrl + "/img/doctors/2.png"), ratings: 4.5, status: "Available", speciality: "Orthopedic", location: "Lahore, Pakistan" },
    //     { name: "Dr. Maria", img: (baseUrl + "/img/doctors/3.png"), ratings: 4.5, status: "Available", speciality: "Orthopedic", location: "Lahore, Pakistan" },
    //     { name: "Dr. Adnan Malik", img: (baseUrl + "/img/doctors/4.png"), ratings: 4.5, status: "Available", speciality: "Orthopedic", location: "Lahore, Pakistan" },
    //     { name: "Dr. Azeem", img: (baseUrl + "/img/doctors/5.png"), ratings: 4.5, status: "Available", speciality: "Orthopedic", location: "Lahore, Pakistan" },
    //     { name: "Dr. Maryam", img: (baseUrl + "/img/doctors/6.png"), ratings: 4.5, status: "Available", speciality: "Orthopedic", location: "Lahore, Pakistan" },
    // ]);

    const [data, setData] = React.useState(JSON.parse(document.getElementById("initial-doctors").value || "[]"));
    const [total, setTotal] = React.useState(parseInt(document.getElementById("initial-total").value || "0"));
    const [pages, setPages] = React.useState(parseInt(document.getElementById("initial-pages").value || "0"));
    const [page, setPage] = React.useState(1);
    const [specialities, setSpecialities] = React.useState(JSON.parse(document.getElementById("initial-specialities").value || "[]"));
    const [loading, setLoading] = React.useState(false);
    const [timerInputName, setTimerInputName] = React.useState(null);
    const [refetch, setRefetch] = React.useState(0);

    async function fetch() {
        setLoading(true);
        const form = document.getElementById("form-filters");

        try {
            const formData = new FormData(form);
            formData.append("page", page);

            const response = await axios.post(
                baseUrl + "/api/doctors/fetch",
                formData
            );

            if (response.data.status == "success") {
                setData(response.data.doctors);
                setTotal(response.data.total);
            } else {
                swal.fire("Error", response.data.message, "error")
            }
        } catch (exp) {
            console.log(exp.message);
            // swal.fire("Error", exp.message, "error")
        } finally {
            setLoading(false);
        }
    }

    React.useEffect(function () {
        if (refetch > 0) {
            fetch();
        }
    }, [refetch]);

    return (
        <>
            <div className="row">
                <div className="col-md-10 center-horizontal">
                    <div className="row mt-3" style={{
                        border: "2px solid black",
                        borderRadius: "20px",
                        padding: "30px 10px"
                    }}>
                        <div className="col-md-12">
                            <span style={{
                                fontSize: "20px",
                                fontWeight: "bold"
                            }}>
                                Showing&nbsp;

                                <span style={{
                                    color: "#822BD4"
                                }}>{ total }</span>&nbsp;Doctors For You
                            </span>
                        </div>

                        <form className="mt-4" id="form-filters">
                            <div className="row g-3">
                                <div className="offset-md-2 col-md-4">
                                    <div className="form-group">
                                        <select name="speciality" className="form-control"
                                            onChange={ function (event) {
                                                fetch();
                                            } }>
                                            <option value="">Select Speciality</option>

                                            { specialities.map(function (speciality, index) {
                                                return (
                                                    <option key={ `speciality-${ index }` }
                                                        value={ speciality.name }>{ speciality.name }</option>
                                                );
                                            }) }
                                        </select>
                                    </div>
                                </div>

                                {/*<div className="col-md-2">
                                    <div className="form-group">
                                        <select name="location" className="form-control">
                                            <option value="">Select Location</option>
                                        </select>
                                    </div>
                                </div>*/}

                                <div className="col-md-4">
                                    <div className="form-group">
                                        <input type="text" name="name" className="form-control" placeholder="Doctors"
                                            onKeyUp={ function () {
                                                clearTimeout(timerInputName);
                                                setTimerInputName(setTimeout(function () {
                                                    fetch();
                                                }, 500));
                                            } } />
                                    </div>
                                </div>

                                {/*<div className="col-md-2">
                                    <div className="form-group">
                                        <select name="date_time" className="form-control">
                                            <option value="">Date & Time</option>
                                        </select>
                                    </div>
                                </div>

                                <div className="col-md-2">
                                    <div className="form-group">
                                        <select name="clinic" className="form-control">
                                            <option value="">Clinic</option>
                                        </select>
                                    </div>
                                </div>*/}

                                <div className="col-md-2">
                                    <div style={{
                                        display: "flex",
                                        gap: "10px",
                                        fontSize: "12px",
                                        position: "relative",
                                        top: "50%",
                                        transform: "translateY(-50%)"
                                    }}>

                                        <button type="submit" disabled={ loading }
                                            className="btn bg-primary-gradient no-border white">Search</button>

                                        {/*<div className="form-check form-switch">
                                            <label className="form-check-label">
                                                Availability &nbsp;
                                                <input className="form-check-input" type="checkbox" defaultChecked />
                                            </label>
                                        </div>

                                        <a href="#" onClick={ function (event) {
                                            event.preventDefault();
                                        } } style={{
                                            color: "#822BD4",
                                            fontWeight: "bold"
                                        }}>Clear All</a>*/}
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div className="row mt-5">
                        { data.map(function (d, index) {
                            return (
                                <div className="col-md-4 mt-3 mb-5" key={ `doctor-${ index }` }>
                                    <div className="border-primary border-width-2" style={{
                                        borderRadius: "10px"
                                    }}>
                                        <div style={{
                                            position: "relative"
                                        }}>
                                            { d.profile_image ? (
                                                <img src={ d.profile_image } style={{
                                                    width: '100%',
                                                    height: '200px',
                                                    objectFit: "cover",
                                                    borderTopLeftRadius: "10px",
                                                    borderTopRightRadius: "10px",
                                                }} />
                                            ) : (
                                                <img src={ `${ baseUrl }/img/doctors/6.png` } style={{
                                                    width: '100%',
                                                    height: '200px',
                                                    objectFit: "cover",
                                                    borderTopLeftRadius: "10px",
                                                    borderTopRightRadius: "10px",
                                                }} />
                                            ) }
                                            

                                            <span style={{
                                                backgroundColor: "#FFB74D",
                                                color: "white",
                                                padding: "3px 10px",
                                                borderRadius: "7px",
                                                position: "absolute",
                                                right: "10px",
                                                top: "10px",
                                                fontSize: "14px"
                                            }}>
                                                <i className="fa-solid fa-star" style={{
                                                    fontSize: "12px",
                                                    marginRight: "5px",
                                                    bottom: "2px",
                                                    position: "relative"
                                                }}></i>
                                                { d.ratings }
                                            </span>
                                        </div>

                                        <div className="container mt-4 ps-3 pe-3">
                                            <div className="row">
                                                <div className="col-md-8">
                                                    <a href={ `${ baseUrl }/doctors/${ d.user_id }/detail` } className="color-primary bold"
                                                        style={{
                                                            fontSize: "20px",
                                                            textDecoration: "none"
                                                        }}>{ d.name }
                                                    </a>
                                                </div>

                                                <div className="col-md-4">
                                                    <span className={ `status status-${ d.status }` }>{ d.status }</span>
                                                </div>
                                            </div>

                                            <p className="mt-3 mb-0" style={{
                                                color: "gray",
                                                fontSize: "12px"
                                            }}>
                                                { d.specialities.map(function (speciality, index) {
                                                    return (
                                                        <span key={ `speciality-${ index }` }>
                                                            { speciality } | &nbsp;
                                                        </span>
                                                    );
                                                }) }

                                                {/*{ d.speciality }*/}
                                            </p>

                                            <hr />

                                            <p className="mt-3 mb-3">
                                                <i className="fa-solid fa-location-dot"></i>&nbsp;&nbsp;&nbsp;
                                                { d.location }
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            );
                        }) }

                        { pages > 1 && (
                            <nav>
                                <ul style={{
                                    listType: "none",
                                    textAlign: "center"
                                }}>
                                    { Array.from({ length: pages }, function (_, index) { return index + 1 } ).map(function (p) {

                                        const isActive = (p == page);

                                        return (
                                            <li key={ `pagination-${ p }` } className={ `page-item ${ isActive ? "active" : "" }` }
                                                style={{
                                                    display: "inline-block",
                                                    marginRight: "15px"
                                                }}>
                                                <a href="#" onClick={ function (event) {
                                                    event.preventDefault();
                                                    setPage(p);
                                                    setRefetch(refetch + 1);
                                                } } style={{
                                                    textDecoration: "none",
                                                    backgroundColor: isActive ? "#8E24AA" : "white",
                                                    color: isActive ? "white" : "black",
                                                    border: (isActive ? "0px" : "1px") + " solid lightgray",
                                                    width: "40px",
                                                    height: "40px",
                                                    borderRadius: "50%",
                                                    display: "block",
                                                    textAlign: "center",
                                                    alignContent: "center",
                                                }}>{ p }</a>
                                            </li>
                                        );
                                    }) }
                                </ul>
                            </nav>
                        ) }
                    </div>
                </div>
            </div>
        </>
    );
}