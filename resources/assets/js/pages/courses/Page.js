import React from 'react'
import {connect} from 'react-redux'
import {Link, Redirect} from 'react-router-dom'
import axios from 'axios'
import {
    Button,
    Container,
    Grid,
    Header,
    Icon,
    Segment,
    Card,
    Image
} from 'semantic-ui-react'
import PageHeader from '../../common/pageHeader'
import Navigation from '../../common/navigation'
import Footer from '../../common/mainFooter'
import {API_URL} from "../../common/url-types";

class Page extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            courses: [],
            message: '',
        }
    }

    componentDidMount() {
        axios.get(`http://rplearning-homolog.siteseguro.ws/api/courses`)
          .then(res => {
            const courses = res.data;
            this.setState({ courses: courses });
        })
    }

    render() {
        const courses = this.state.courses;
        const { isAuthenticated } = this.props;
        return (
            <div>
                <Navigation/>
                <main className="fadeIn animated">
                    <PageHeader heading="Cursos"/>
                    <Segment vertical textAlign='center' style={{minHeight: '100vh'}}>
                        <Header as='h1'>Cursos</Header>
                        <Container>
                            <Card.Group itemsPerRow={4}>
                                { courses.map((course) => 
                                <Card color='red' key={course.id}>
                                    <Image src='https://react.semantic-ui.com/images/avatar/large/matthew.png' />
                                    <Card.Content>
                                        <Card.Header>{ course.title }</Card.Header>
                                        <Card.Meta>
                                        <span className='date'>Criado em { course.created_at }</span>
                                        </Card.Meta>
                                        <Card.Description>{ course.description }</Card.Description>
                                    </Card.Content>
                                    <Card.Content extra>
                                    <div className='ui two buttons'>
                                        <Button basic color='green' as={Link} to={"/courses/" + course.id + '/details'}>
                                            Detalhes
                                        </Button>
                                        {/*{ isAuthenticated ?*/}
                                        {/*<Button basic color='red'>*/}
                                            {/*Excluir*/}
                                        {/*</Button>*/}
                                        // : null }
                                        </div>
                                    </Card.Content>
                                </Card>
                                    )
                                }
                            </Card.Group>
                        </Container>
                    </Segment>
                </main>
                <Footer/>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        isAuthenticated : state.Auth.isAuthenticated,
    }
};

export default connect(mapStateToProps)(Page);
