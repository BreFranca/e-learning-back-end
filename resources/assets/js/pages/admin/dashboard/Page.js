import React from 'react'
import axios from 'axios'
import { Link } from 'react-router-dom'
import { API_URL } from "../../../common/url-types"
import Admin from '../Admin'
import {
    Grid,
    Card,
    Table,
    TableHead,
    TableBody,
    TableRow,
    TableCell,
    CardHeader,
    CardContent,
    CardActions,
    Button,
    Fab,
    Tooltip
} from '@material-ui/core'

import AddIcon from '@material-ui/icons/Add'
import NoteIcon from '@material-ui/icons/Note'

class Page extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            users: [],
            courses: []
        }
    }

    getData = () => {
        axios.get(`${API_URL}/api/dashboard/${this.props.user.id}`)
            .then(res => {
                const { data } = res;
                console.log(data);
                this.setState({
                    users: data.leaderboard,
                    courses: data.courses
                });
            })
    }

    componentDidMount() {
        this.getData();
    }

    render() {
        const { user } = this.props;
        const { users, courses } = this.state;
        return (
            <Admin heading="Painel de Controle">
                <Grid container spacing={40}>
                    <Grid item sm={6}>
                        <Card>
                            <CardHeader title={user.role_id === '1' || user.role_id === 1 ? 'Cursos' : 'Meus cursos'}></CardHeader>
                            <Table>
                                <TableHead>
                                    <TableRow>
                                        <TableCell>Título</TableCell>
                                        <TableCell>Duração</TableCell>
                                    </TableRow>
                                </TableHead>
                                <TableBody>
                                    {courses.length > 0 ?
                                        courses.map((course, index) =>
                                            <TableRow key={index}>
                                                <TableCell>{course.title}</TableCell>
                                                <TableCell>{course.duration}</TableCell>
                                            </TableRow>
                                        )
                                        :
                                        <TableRow>
                                            <TableCell>Você ainda não tem cursos criados</TableCell>
                                            <TableCell></TableCell>
                                        </TableRow>
                                    }
                                </TableBody>
                            </Table>
                            <CardActions>
                                {user.role_id === '1' || user.role_id === 1 ?
                                    <Button component={Link} color="secondary" variant="contained" to="/admin/courses">
                                        Ver Todos
                                </Button>
                                    :
                                    <Button component={Link} color="secondary" variant="contained" to="/my-courses">
                                        Ver Todos
                                </Button>
                                }

                                {user.role_id != '3' || user.role_id != 3 ?
                                <Button component={Link} variant="contained" to="/admin/courses/create">
                                    Criar novo
                                    <AddIcon />
                                </Button>
                                : null }
                            </CardActions>
                        </Card>
                    </Grid>
                    {user.role_id === '1' || user.role_id === 1 ?
                        <Grid item sm={6}>
                            <Card>
                                <CardHeader title="Ranking"></CardHeader>
                                <Table>
                                    <TableHead>
                                        <TableRow>
                                            <TableCell>Nome</TableCell>
                                            <TableCell>Pontos</TableCell>
                                            <TableCell>Level</TableCell>
                                        </TableRow>
                                    </TableHead>
                                    <TableBody>
                                        {/* {users.map((user, index) =>
                                            <TableRow key={index}>
                                                <TableCell>{user.name}</TableCell>
                                                <TableCell>{user.points}</TableCell>
                                                <TableCell>{user.level}</TableCell>
                                            </TableRow>
                                        )} */}
                                    </TableBody>
                                </Table>
                            </Card>
                        </Grid>
                        : null}
                    {user.role_id === '3' || user.role_id === 3 ?
                        <Grid item sm={6}>
                            <Card>
                                <CardHeader title="Recompensas"></CardHeader>
                                <CardContent>
                                    <Tooltip title="Certificado 1" aria-label="Certificado 1">
                                        <Fab color="default" style={{ margin: '0 4px' }}>
                                            <NoteIcon />
                                        </Fab>
                                    </Tooltip>
                                    <Tooltip title="Certificado 2" aria-label="Certificado 2">
                                        <Fab color="default" style={{ margin: '0 4px' }}>
                                            <NoteIcon />
                                        </Fab>
                                    </Tooltip>
                                    <Tooltip title="Certificado 3" aria-label="Certificado 3">
                                        <Fab color="default" style={{ margin: '0 4px' }}>
                                            <NoteIcon />
                                        </Fab>
                                    </Tooltip>
                                </CardContent>
                            </Card>
                            <br />
                            <Card>
                                <CardHeader title="Certificados"></CardHeader>
                                <CardContent>
                                    <Tooltip title="Certificado 1" aria-label="Certificado 1">
                                        <Fab color="default" style={{ margin: '0 4px' }}>
                                            <NoteIcon />
                                        </Fab>
                                    </Tooltip>
                                    <Tooltip title="Certificado 2" aria-label="Certificado 2">
                                        <Fab color="default" style={{ margin: '0 4px' }}>
                                            <NoteIcon />
                                        </Fab>
                                    </Tooltip>
                                    <Tooltip title="Certificado 3" aria-label="Certificado 3">
                                        <Fab color="default" style={{ margin: '0 4px' }}>
                                            <NoteIcon />
                                        </Fab>
                                    </Tooltip>
                                </CardContent>
                            </Card>
                        </Grid>
                        : null}
                </Grid>
            </Admin>
        );
    }
}

export default Page;